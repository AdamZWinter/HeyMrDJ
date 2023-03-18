# HeyMrDJ
Digital song requests for live DJ at events.



For this assignment you will be creating Version One of your Final Project using Fat-free and MVC, as well as GitHub.

Begin by creating a private GitHub repo for your project. Add me and your teammate(s) as Collaborators. I will be looking for a clear history of commits from all team members.

Version One requirements:
<br>--Fat-free is utilized correctly.
<br>--The site has a title and favicon.
<br>--A home page makes it clear what the project is about, and includes at least one image.
<br>--Routing is enabled and works properly. A second page is accessible from the home page.
<br>--Bootstrap is used for layout.
<br>--HTML is valid.  https://html5.validator.nu/
<br>--There is a clear separation of concerns.
<br>--Files are organized into appropriate directories, including view, images, styles, etc.
<br>--Files you created are under version control. Files you did not create are not under version control. GitHub comments are clear.
<br>--Code is well-commented and code style (variable names, curly braces, etc.) is consistent.

<br>
<br>
Project Requirements

<br><i>--Separates all database/business logic using the MVC pattern.</i>
<br> Views are in views/, models are in models/, controllers are in controllers/
<br> I tried to make JS a subfolder of views/, but the relative paths didn't work
<br>
<br><i>--Routes all URLs and leverages a templating language using the Fat-Free framework.</i>
<br> All links within the site reference only routes, not any particular files:  index.php
<br>
<br><i>--Has a clearly defined database layer using PDO and prepared statements.</i>
<br>  see models/DataLayer.php
<br>
<br><i>--Data can be added and viewed.</i>
<br>  Please register and sign in.  Within models/DataLayer.php and models/User.php
<br>  you'll see that users are stored in the users database and that passwords are properly hashed and verified
<br>
<br><i>--Has a history of commits from both team members to a Git repository. Commits are clearly commented.</i>
<br>
<br>
<br><i>--Uses OOP, and utilizes multiple classes, including at least one inheritance relationship.</i>
<br> A DJ is a User.  The DJ class extends the User class.  Other classes with OOP design include PostedObj, DataLayer, Song, and Playlist.
<br> The controller classes utilize static methods.
<br>
<br><i>--Contains full Docblocks for all PHP files and follows PEAR standards.</i>
<br>
<br>
<br><i>--Has full validation on the server side through PHP.</i>
<br> Validation is done via the PostedObj class, and invoked by the controller.
<br> See HomePage.php HomePage::postRegister() method for example.
<br>
<br><i>--All code is clean, clear, and well-commented. DRY (Don't Repeat Yourself) is practiced.</i>
<br> With the PostedObj class, validation of same kinds of data, posted from different pages, happens with a single method
<br> For example, the same code is used to validate an email address, on the server side, whether posted from the register page or the sign in page.
<br>
<br><i>--Your submission shows adequate effort for a final project in a full-stack web development course.</i>
<br>
<br>
<br><i>--Incorporates Ajax that access data from a JSON file, PHP script, or API. If you implement Ajax, be sure to include how you did so in your readme file.</i>
<br>This is the default implementation for client-to-server POST
<br>That is, in many places the front end relationship to the backend is through such an API.
<br>For example, see JS/register.js and HomePage::postRegister()

