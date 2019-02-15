<?php
class User
{
    private $id;
    private $userName;
    private $hashPass;
    private $email;
    public function __construct()
    {
        $this->id = -1;
        $this->userName = '';
        $this->email = '';
        $this->hashPass = '';
    }
    public function getId()
    {
        return $this->id;
    }
    public function getUserName()
    {
        return $this->userName;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getHashPass()
    {
        return $this->hashPass;
    }
    public function setHashPass($newPass)
    {
        $newHashedPass = password_hash($newPass, PASSWORD_BCRYPT);
        $this->hashPass = $newHashedPass;
        return $this;
    }
    public function setUserName(string $userName)
    {
        $this->userName = $userName;
        return $this;
    }
    public function setEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Nie poprawny format adresu email');
        }
        $this->email = $email;
        return $this;
    }
    static public function loadUserById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM users WHERE id=:id');
        try {
            $stmt->execute([
                'id' => $id,
            ]);
        } catch (PDOException $exception) {
            throw new \Exception('Wystąpił błąd podczas pobierania uzytkownika');
        }
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->userName = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }
    static public function loadAllUsers(PDO $conn) {
        $result = [];
        try {
            $users = $conn->query('SELECT * FROM users');
        } catch (PDOException $exception) {
            throw new \Exception('Wystąpił błąd podczas pobierania wszysstkich użytkowników');
        }
        if (false !== $users && 0 != $users->rowCount()) {
            foreach ($users as $user) {
                $loadedUser = new User();
                $loadedUser->id = $user['id'];
                $loadedUser->userName = $user['username'];
                $loadedUser->hashPass = $user['hash_pass'];
                $loadedUser->email = $user['email'];
                $result[] = $loadedUser;
            }
        }
        return $result;
    }
    public function saveToDB(PDO $conn)
    {
        if (-1 === $this->id) {
            $this->createUser($conn);
            return true;
        }
        return $this->updateUser($conn);
    }
    private function createUser(PDO $conn)
    {
        $stmt = $conn->prepare('INSERT INTO users (username, email, hash_pass) VALUES (:username, :email, :hash_pass)');
        try {
            $stmt->execute([
                'email' => $this->email,
                'hash_pass' => $this->hashPass,
                'username' => $this->userName,
            ]);
        } catch (PDOException $exception) {
            throw new \Exception('Błąd podczas dodawania użytkownika do bazy: ');
        }
        $this->id = $conn->lastInsertId();
    }
    private function updateUser(PDO $conn)
    {
        $stmt = $conn->prepare('UPDATE users SET username=:username, email=:email, hash_pass=:hash_pass WHERE id=:id');
        try {
            $result = $stmt->execute([
                'email' => $this->email,
                'hash_pass' => $this->hashPass,
                'id' => $this->id,
                'username' => $this->userName,
            ]);
        } catch (PDOException $exception) {
            throw new \Exception('Błąd podczas aktualizacji uzytkownika');
        }
        return $result;
    }
    public function delete(PDO $conn)
    {
        if (-1 === $this->id) {
            return false;
        }
        $stmt = $conn->prepare('DELETE FROM users WHERE id=:id');
        try {
            $result = $stmt->execute([
                'id' => $this->id
            ]);
        } catch (PDOException $exception) {
            throw new \Exception('Wystąpił błąd podczas usuwania użytkownika');
        }
        if ($result) {
            $this->id = -1;
            return true;
        }
    }
}