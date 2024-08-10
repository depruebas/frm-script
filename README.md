# scripts-framework
simple framework to run scripts on server

Environment to execute scripts in php.

How does it work?
<br><br>
<b>php init.php Test Init 'param1 param2 param3'</b>
<br><br>
Test is the name of the class and file ( Test.php into the scripts directory) <br>
Init is the name of the method into class Test<br>

The framework has a 
<ul>
<li>a class to catch errors
<li>a class to access MySql, postgreSQL using PDO
<li>a class to record logs
<li>a class to read configuration files.
</ul>
