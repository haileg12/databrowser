Data Browser - Student Project
  * Data stored in a JSON file is read into a sql database. 
  * The HTML webpage sends a request to receive the data and displays it one at a time for user interaction.
  * Allows the user to manage data stored in server side and have it displayed on the client side.

How to use:
  * Download zip
  * Place databrowser folder in the htdocs within the xampp directory
  * Run the "create_db.php" file in your browser by writing the url: "localhost/databrowser/create_db.php"
       * this will read the data from "opdata.json" and create a mysql database
  * Open the databrowser.html file, change the path in the url section to "localhost/databrowser/databrowser.html"
  * Click "load data" button to load data from the server database.
  * Now you're free to use the rest of the buttons to edit, add, sort, and view the data!

Example for you to try:
  * Click "Insert" and form will display below.
  * Fill in the form with this information given below:
    * Name: Ace
    * Age: 20
    * Gender: Male
    * Height: 6'1"
    * Straw Hat Crew Member?: No (unchecked)
    * Included in the "test_image" folder is an image for a character.
      * Click "Choose Image", select "ace.jpg" within this folder.
      * Click "Upload Image" and it will automatically fill in the image path.
      * Click "Save New Item" and you're done!