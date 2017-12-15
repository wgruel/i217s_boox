<?php

  /**

    This is the book controller.
    It is the connection between the model (data storage) and the view.
    It contains all important actions regarding the books and is controlling
    data processing and views...

    Not yet done:
    - Think about: does ownership change if admin edits a book? (makes it more transparent, but this is not ownership...)
    - Link owner and book (view)
    - More fields for Books (e.g. Description, Picture, ISBN)
    - Show name and information of book-owner
    - Testing / Bugfixing
    - Extensive documentation
    - Security (e.g. make sure underprivilleged users are not allowed to add, edit, update a user)

    NOTE: If you want to know more, check comments in user-controller...

  */

  class BookController {
    public $book = null;
    public $view = null;

    public function __construct(){
      // user always needs to be included...
      // make sure you do something like that in your index.php
      require_once('models/book.php');
      $this->book = new Book();
    }

    /**
      show users as list
    */
    public function indexAction() {
      $books = Book::getAllBooks();
      require_once('views/bookList.php');
    }
    /**
      provide form do edit user...
    */
    public function editAction($id){
      $book = Book::getBook($id);
      $action = "save";
      $actionText = "Update Book";
      require_once('views/bookForm.php');
    }

    /**
      save a book that has been edited
    */
    public function saveAction($postarray){
      $updateStatus = $this->book->update($postarray['id'], $postarray['author'], $postarray['title'], $_SESSION['user']->getID(), $postarray['price']);
      $book = $this->book->getBook($postarray['id']);
      $message = $updateStatus[1];
      require_once('views/bookForm.php');
    }

    /**
      provide a form to create a new book ...
    */
    public function createAction(){
      $action = "new";
      $actionText = "Create Book";
      // create empty book...
      $book = new Book();
      require_once('views/bookForm.php');
    }

    /**
      save a book that has just been entered
    */
    public function newAction($postarray){
      $createStatus = $this->book->create($postarray['author'], $postarray['title'], $_SESSION['user']->getID(), $postarray['price']);
      // If successfull insert
      $message = $createStatus[1];
      if ($createStatus[0]){
        $book = $this->book->getBook($createStatus[2]);
      }
      // if not, we create a new book and show the form again...
      else {
        $book = new Book();
      }
      require_once('views/bookForm.php');
    }

    /**
      Delete book
    */
    public function deleteAction($id){
      $deleteStatus = $this->book->delete($id);
      $message = $deleteStatus[1];
      $books = $this->book->getAllBooks();
      require_once('views/bookList.php');
    }



  }
?>
