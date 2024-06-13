<?php
	include("../classes/DBUtil.php");
	include("../classes/UserUtil.php");

    function main() {
        $db = DBUtil::getInstance()->getDB();
        $user = UserUtil::getInstance();

        $email = prompt("Email: ");
        $password = prompt("Password: ");
        $passwordConfirm = prompt("Confirm Password: ");
        $reonEmail = prompt("DION Account (8 chars): ");

        if (!isEmailAvailable($db, $email)) {
            exit("Email is unavailable");
        }

        $result = $user->createUser($email, $reonEmail, $password, $passwordConfirm);

        $detail = match ($result) {
            0 => "Account created!",
            1 => "Invalid email",
            2 => "DION Email is invalid or unavailable",
            3 => "Passwords do not match",
            4 => "Password does not minimum requirements",
        };

        echo $detail."\n";
        exit($result);
    }

    function prompt($s) {
        echo $s;
        return rtrim(fgets(STDIN));
    }

    function isEmailAvailable($db, $email) {
        $stmt = $db->prepare("select id from sys_users where email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (isset($row)) return false;
        return true;
    }

    main();