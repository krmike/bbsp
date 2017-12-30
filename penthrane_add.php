<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 22:31
 */

include('config.php');

$resourceId = 21; // Penthrane

$Access = new Access();
$DB = new Db();
$User = new User();
$Penthrane = new Penthrane();

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);

if ($_POST['save']) {
	$data = array(
		"operation"     => $_POST['operation'], // 1 - add, 2 - remove
		"qty"			=> intval($_POST['qty']),
		"date"			=> date("Y-m-d H:i:s"),
		"user_id"  		=> $_COOKIE['user_id'],
		"comment"		=> $_POST['comment']
	);
	$stockId = $Penthrane->add($data);
	header("location:/penthrane.php");
}

page_header(8, "Penthrane Stock");

?>
	<form method="post">
		<table class="table">
			<tr>
				<td class="right-cell">
					Operation
				</td>
				<td>
					<input type="radio" name="operation" value="1" checked id="operationAdd"/>
					<label for="operationAdd">Add</label>&nbsp;&nbsp;
					<input type="radio" name="operation" value="2" id="operationRemove">
					<label for="operationRemove">Remove</label>
				</td>
			</tr>
			<tr>
				<td class="right-cell">
					Quantity (Viles):
				</td>
				<td>
					<input type="text" required name="qty" class="numeric"/>
				</td>
			</tr>
			<tr>
				<td class="right-cell">
					Comment:
				</td>
				<td>
					<textarea required name="comment"></textarea>
				</td>
			</tr>
			<tr>
				<td class="right-cell">

				</td>
				<td>
					<input type="submit" class="submit" name="save" value="Save">
				</td>
			</tr>
		</table>
	</form>
<?

page_footer();

?>