$passwordToStore = md5(md5($_POST['password']));

$sql = "INSERT INTO agents (idFiscal, nom, prenom, email, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$idFiscal, $nom, $prenom, $email, $passwordToStore]);

