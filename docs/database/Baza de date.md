
## Baza de date

SQL 

Am ales aceasta modalitate de a salva datele intr-o schema ralationala deoarece acest proiect necesita o schema simpla, numarul de cereri vor fi relativ mici si sunt deja familiarizata cu aceasta medota.

Cand un user se conecteaza la baza de data, o functie de criptate va fi folosita pe parola si aceasta va fi stocata in baza de date. 

```
    $CONFIG = [
        'servername' => "localhost",
        'username' => "root",
        'password' => '',
        'db' => 'storyweb'
    ];

    $conn = new mysqli($CONFIG["servername"], $CONFIG["username"], $CONFIG["password"], $CONFIG["db"]);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
```

## Autentificare

Database Hashed Password Authentification


1. Functia de login si de criptare a parolei: 

```
function login($username, $password) {
        GLOBAL $conn;

        $hashedPassword = md5($password);
        $loginStmt = $conn -> prepare('SELECT * FROM users WHERE username = ? AND password = ?');
        $loginStmt -> bind_param('ss', $username, $hashedPassword);

        $loginStmt -> execute();
        $results = $loginStmt -> get_result();
        $loginStmt -> close();

        
        if($results -> num_rows  === 1) {
            $firstRow = $results -> fetch_assoc();

            return new User($firstRow['id'], $firstRow['nume'], $firstRow['prenume'], $firstRow['username'],$firstRow['email'],
            $firstRow['password']);
        } 

        return NULL;
    }

```

2. Functia de logare

```
    function login($username, $password) {
        GLOBAL $conn;

        $hashedPassword = md5($password);
        $loginStmt = $conn -> prepare('SELECT * FROM users WHERE username = ? AND password = ?');
        $loginStmt -> bind_param('ss', $username, $hashedPassword);

        $loginStmt -> execute();
        $results = $loginStmt -> get_result();
        $loginStmt -> close();

        
        if($results -> num_rows  === 1) {
            $firstRow = $results -> fetch_assoc();

            return new User($firstRow['id'], $firstRow['nume'], $firstRow['prenume'], $firstRow['username'],$firstRow['email'],
            $firstRow['password']);
        } 

        return NULL;
    }
```

3. Functia de verificare daca un user are sesiunea setata (este logat)

```
    function getLoggedUser($username, $hashedPassword) {
        GLOBAL $conn;

        $loginStmt = $conn -> prepare('SELECT * FROM users WHERE username = ? AND password = ?');
        $loginStmt -> bind_param('ss', $username, $hashedPassword);

        $loginStmt -> execute();
        $results = $loginStmt -> get_result();
        $loginStmt -> close();

        
        if($results -> num_rows  === 1) {
            $firstRow = $results -> fetch_assoc();

            return new User($firstRow['id'], $firstRow['nume'], $firstRow['prenume'], $firstRow['username'],$firstRow['email'], $firstRow['password']);
        } 

        return NULL;
    }
```

Am ales aceasta durata de expirare a tokenului fiindca citirea unei povesti de catre un utilizator poate lua mai multe ore, insa dupa 4 ore, cand tokenul expira, utilizatorul are ocazia de a lua o pauza.