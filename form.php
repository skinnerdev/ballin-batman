<HTML>
<HEAD>
</HEAD>
<BODY>
<form action="form_script.php" method="POST">

<p>Name: <input type="text" name="name" size="30"/></p>

<p>Number: <input type="number" name="num" size="30"/></p>

<p>Shirt Size:
<select name="size">
<option value="small">Small</option>
<option value="medium">Medium</option>
<option value="large">Large</option>
</select></p>

<p>Gender:
<input type="radio" name="gender" value="male"/>
Male
<input type="radio" name="gender" value="female"/>
Female</p>

<textarea rows="20" cols="20"
name="textarea">default text</textarea>  <!--the default text appears in the box to start-->

<input type="submit" name="submit" value="Submit"/>


</BODY>
</HTML>