<?php 


if($_SERVER["REQUEST_METHOD"] == "POST"){
	if (isset($_POST['log'])){
		
		$overenie =  dibi::select('user, password, admin')->from('users')->where('user=%s', $_POST['user'])->fetchAll();
		if($overenie != false){
			if(($overenie[0]['password']== $_POST['pass']) && ($overenie[0]['admin']==1)){
				$_SESSION['prihlasenie']=true;
			}else {
				echo 'chybne meno alebo heslo';
			}
		}else{
				echo 'chybne meno alebo heslo';
		}
	}
}
else{
?>

<div id="login">
	<?php
	
	if(isset($_SESSION['prihlasenie']) && $_SESSION['prihlasenie'] == true){
	echo '<p style="text-align:center"> Už si prihlásený </p>';
	}else{
	?>
	<h2>Prihlásenie:</h2>
	<?php if (isset($_COOKIE['error'])&&($_COOKIE['error']!="")){echo "<p>".$_COOKIE['error']."</p>"; setcookie('error');}?>
	<form action='' method='post'> 
		<table>
			<tr>
				<td class="text">Uživateľ:</td>
				<td>
					<input type="text" name="user" maxlength="20">
				</td>
			</tr>
			<tr>
				<td class="text">Heslo:</td>
				<td>
					<input type="password" name="pass" maxlength="20">
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input class="submit" type='submit' name="log" value="Prihlásiť sa" >
				</td>
			</tr>
		</table>
	</form>
	<?php
	}
	?>
</div>
<?php }?>