<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require_once dirname(__DIR__)."/vendor/autoload.php";
	require_once("DBUtil.php");
	require_once("ConfigUtil.php");
	require_once("TemplateUtil.php");
	
	class UserUtil {

		private static $instance;

		private final function  __construct() {
		}

		public static function getInstance() {
			if(!isset(self::$instance)) {
				self::$instance = new UserUtil();
			}
			return self::$instance;
		}
		
		public function registerUser($email, $username, $password, $passwordConfirm) {
			
		}
		
		public function changePassword($password, $newPassword, $newPasswordConfirm) {
			if ($newPassword != $newPasswordConfirm) return 3;
			if (!self::$instance->verifyPassword($password)) return 1;
			
			if (!self::$instance->setPassword($_SESSION["user_id"], $newPassword)) return 2;
			
			return 0;
		}
		
		private function verifyPassword($password) {
			$db = DBUtil::getInstance()->getDB();
			$stmt = $db->prepare("select password from sys_users where id = ?");
			$stmt->bind_param("i", $_SESSION["user_id"]);
			$stmt->execute();
			$passwordDb = $stmt->get_result()->fetch_assoc()["password"];
			if (password_verify($password, $passwordDb)) return true;
			return false;
		}
		
		private function validatePasswordConstraints($password) {
			if (mb_strlen($password, "UTF-8") < 8) return false;
			if (strlen($password) >= 72) return false;
			return true;
		}
		
		private function setPassword($id, $password) {
			if (!self::$instance->validatePasswordConstraints($password)) return false;
			
			$password_hash = self::$instance->getPasswordHash($password);
			$db = DBUtil::getInstance()->getDB();
			$stmt = $db->prepare("update sys_users set password = ? where id = ?");
			$stmt->bind_param("si", $password_hash, $id);
			$stmt->execute();
			
			return true;
		}
		
		private function getPasswordHash($password) {
			return password_hash($password, PASSWORD_DEFAULT);
		}
		
		public function requestEmailChangeAction($password, $newEmail) {
			if (!self::$instance->validateEmail($newEmail)) return 2;
			if (!self::$instance->verifyPassword($password)) return 1;
			
			$db = DBUtil::getInstance()->getDB();
			$stmt = $db->prepare("select count(*) from sys_users where email = ?");
			$stmt->bind_param("s", $newEmail);
			$stmt->execute();
			if ($stmt->get_result()->fetch_assoc()["count(*)"] != 0) return 0;
			
			$key = base64_encode(random_bytes(36));
			$stmt = $db->prepare("replace into sys_email_change (user_id, new_email, secret) values (?,?,?)");
			$stmt->bind_param("iss", $_SESSION["user_id"], $newEmail, $key);
			$stmt->execute();
			
			self::$instance->sendConfirmationEmail($_SESSION["user_id"], $key);
			
			return 0;
		}
		
		public function confirmEmailChangeAction($id, $key) {
			$db = DBUtil::getInstance()->getDB();
			$stmt = $db->prepare("select new_email from sys_email_change where user_id = ? and secret = ? and time > date_sub(now(), interval 1 day)");
			$stmt->bind_param("is", $id, $key);
			$stmt->execute();
			$new_email = $stmt->get_result()->fetch_assoc()["new_email"];
			if (!isset($new_email)) return 1;
			
			$stmt = $db->prepare("delete from sys_email_change where user_id = ? and secret = ?");
			$stmt->bind_param("is", $id, $key);
			$stmt->execute();
			
			$stmt = $db->prepare("update sys_users set email = ? where id = ?");
			$stmt->bind_param("si", $new_email, $id);
			$stmt->execute();
			
			return 0;
		}
		
		private function validateEmail($email) {
			return filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE);
		}
		
		private function sendConfirmationEmail($id, $key) {
			$hostname = ConfigUtil::getInstance()->getConfig()["hostname"];
			$email_domain = ConfigUtil::getInstance()->getConfig()["email_domain"];
			$from = "noreply@".$email_domain;
			$subject = "REON email address confirmation";
			$message = TemplateUtil::render("/email/confirmation_email", [
				"hostname" => $hostname,
				"id" => $id,
				"key" => urlencode($key)
			]);
			
			self::$instance->sendUtf8Email($email, $from, $subject, $message);
			
			return 0;
		}
		
		public function sendPasswordResetEmail($email) {
			$db = DBUtil::getInstance()->getDB();
			
			$stmt = $db->prepare("select id from sys_users where email = ?");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_assoc();
			if (!isset($row)) return 1;
			$user_id = $row["id"];
			
			$stmt = $db->prepare("select count(*) from sys_password_reset where user_id = ? and time > date_sub(now(), interval 5 minute)");
			$stmt->bind_param("i", $user_id);
			$stmt->execute();
			if ($stmt->get_result()->fetch_assoc()["count(*)"] > 0) return 2;
			
			$key = base64_encode(random_bytes(36));
			$stmt = $db->prepare("replace into sys_password_reset (user_id, secret) values (?, ?)");
			$stmt->bind_param("is", $user_id, $key);
			$stmt->execute();
			
			$hostname = ConfigUtil::getInstance()->getConfig()["hostname"];
			$email_domain = ConfigUtil::getInstance()->getConfig()["email_domain"];
			$from = "noreply@".$email_domain;
			$subject = "REON account password reset";
			$message = TemplateUtil::render("/email/forgot_password_email", [
				"hostname" => $hostname,
				"id" => $user_id,
				"key" => urlencode($key)
			]);
			
			self::$instance->sendUtf8Email($email, $from, $subject, $message);
			
			return 0;
		}
		
		private function sendUtf8Email($to, $from, $subject, $message) {
			$mail = new PHPMailer();
			$mail->CharSet = PHPMailer::CHARSET_UTF8;
			$mail->Encoding = PHPMailer::ENCODING_QUOTED_PRINTABLE;
			$mail->isSendmail();
			$mail->setFrom($from);
			$mail->addAddress($to);
			$mail->Subject = $subject;
			$mail->msgHTML($message);
			$mail->send();
		}
		
		public function resetPassword($id, $key, $password, $passwordConfirm) {
			$verify_success = self::$instance->verifyResetPassword($id, $key);
			if ($verify_success != 0) return 1;
			if ($password != $passwordConfirm) return 2;
			
			if (!self::$instance->setPassword($id, $password)) return 3;
			
			$stmt = $db->prepare("delete from sys_password_reset where user_id = ? and secret = ?");
			$stmt->bind_param("is", $id, $key);
			$stmt->execute();
			
			return 4;
		}
		
		public function verifyResetPassword($id, $key) {
			$db = DBUtil::getInstance()->getDB();
			$stmt = $db->prepare("select count(*) from sys_password_reset where user_id = ? and secret = ? and time > date_sub(now(), interval 1 day)");
			$stmt->bind_param("is", $id, $key);
			$stmt->execute();
			if ($stmt->get_result()->fetch_assoc()["count(*)"] == 0) return 1;
			
			return 0;
		}
		
		public function rerollLoginPassword() {
			$new_password = self::$instance->generateLogInPassword();
			$db = DBUtil::getInstance()->getDB();
			$stmt = $db->prepare("update sys_users set log_in_password = ? where id = ?");
			$stmt->bind_param("si", $new_password, $_SESSION["user_id"]);
			$stmt->execute();
		}
		
		private function generateLogInPassword() {
			$allowed_chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$password = "";
			for ($i = 0; $i < 8; $i++) {
				$password .= $allowed_chars[random_int(0, strlen($allowed_chars) - 1)];
			}
			return $password;
		}
		
		public function sendSignupEmailAction($policiesAccepted, $email) {
			if (!isset($policiesAccepted)) return 1;
			if (!self::$instance->validateEmail($email)) return 2;
			
			$db = DBUtil::getInstance()->getDB();
			
			$stmt = $db->prepare("select id from sys_users where email = ?");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_assoc();
			if (isset($row)) return 0;
			
			$stmt = $db->prepare("select count(*) from sys_signup where email = ? and time > date_sub(now(), interval 5 minute)");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			if ($stmt->get_result()->fetch_assoc()["count(*)"] > 0) return 0;
			
			$key = base64_encode(random_bytes(36));
			$stmt = $db->prepare("insert into sys_signup (email, secret) values (?, ?)");
			$stmt->bind_param("ss", $email, $key);
			$stmt->execute();
			$signup_id = $db->insert_id;
			
			$hostname = ConfigUtil::getInstance()->getConfig()["hostname"];
			$email_domain = ConfigUtil::getInstance()->getConfig()["email_domain"];
			$from = "noreply@".$email_domain;
			$subject = "REON Sign-up Request";
			$message = TemplateUtil::render("/email/signup", [
				"hostname" => $hostname,
				"id" => $signup_id,
				"key" => urlencode($key)
			]);
			
			self::$instance->sendUtf8Email($email, $from, $subject, $message);
			
			return 0;
		}
		
		public function verifySignupRequest($id, $key) {
			$db = DBUtil::getInstance()->getDB();
			$stmt = $db->prepare("select email from sys_signup where id = ? and secret = ? and time > date_sub(now(), interval 1 day)");
			$stmt->bind_param("ss", $id, $key);
			$stmt->execute();
			$email = $stmt->get_result()->fetch_assoc()["email"];
			//if (!isset($email) return 1;
			
			return $email;
		}
		
		public function completeSignupAction($id, $key, $reonEmail, $password, $passwordConfirm) {
			$email = self::$instance->verifySignupRequest($id, $key);

			$result = self::$instance->createUser($email, $reonEmail, $password, $passwodConfirm);
			if ($result > 0) {
				return $result;
			}

			$stmt = $db->prepare("delete from sys_signup where email = ?");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			
			return 0;
		}

		public function createUser($email, $reonEmail, $password, $passwordConfirm) {
			if (!isset($email)) return 1;
			if (!self::$instance->isDionEmailValidAndFree($reonEmail)) return 2;
			if ($password != $passwordConfirm) return 3;
			if (!self::$instance->validatePasswordConstraints($password)) return 4;
			
			$password_hash = self::$instance->getPasswordHash($password);
			$dion_ppp_id = self::$instance->generatePPPId();
			$log_in_password = self::$instance->generateLogInPassword();
			$db = DBUtil::getInstance()->getDB();
			$stmt = $db->prepare("insert into sys_users (email, password, dion_ppp_id, dion_email_local, log_in_password, money_spent) values (?, ?, ?, ?, ?, 0)");
			$stmt->bind_param("sssss", $email, $password_hash, $dion_ppp_id, $reonEmail, $log_in_password);
			$stmt->execute();

			return 0;
		}
		
		private function isDionEmailValidAndFree($email_local) {
			if (strlen($email_local) != 8) return false;
			if (!preg_match("/^[a-z0-9]+$/", $email_local)) return false;
			
			$db = DBUtil::getInstance()->getDB();
			$stmt = $db->prepare("select count(*) from sys_users where dion_email_local = ?");
			$stmt->bind_param("s", $email_local);
			$stmt->execute();
			if ($stmt->get_result()->fetch_assoc()["count(*)"] != 0) return false;
			
			return true;
		}
		
		private function generatePPPId() {
			$allowed_chars = "0123456789";
			do {
				$ppp_id = "g";
				for ($i = 0; $i < 9; $i++) {
					$ppp_id .= $allowed_chars[random_int(0, strlen($allowed_chars) - 1)];
				}
				
				$db = DBUtil::getInstance()->getDB();
				$stmt = $db->prepare("select count(*) from sys_users where dion_ppp_id = ?");
				$stmt->bind_param("s", $ppp_id);
				$stmt->execute();
				$ppp_id_free = $stmt->get_result()->fetch_assoc()["count(*)"] == 0;
			} while (!$ppp_id_free);
			
			return $ppp_id;
		}
	}
?>
