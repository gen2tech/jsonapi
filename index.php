<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
require 'vendor/autoload.php';
require 'config/config.php';
use Inf\Router;
use App\Helper;

$router = new Router([
  'paths' => [
      'controllers' => 'app/Controllers',
  ],
  'namespaces' => [
      'controllers' => 'App\Controllers',
  ],
]);



$router->get('/', 'Main@getDocs');
// Each request without any request parameter comes by default with order of ASC, page 1 and limit 20
// eg. /posts

// Available on Posts
//   /posts

//   /post/1                                    // Get Post By ID 
//   /posts?page=2                              // Adding Page Number 
//   /posts?page=2&limit=5                      // Adding Page Number and Page limit
//   /posts/desc                                // List in descending order
//   /posts/desc?page=2&limit=5                 // List in descending order Adding Page Number and Page limit
//   /posts_by_user/userId                      // List by user
//   /posts_by_user/userId/desc                 // List by userin descending order
//   /posts_by_user/userId/desc?page=2&limit=5  // List by user in descending order Adding Page Number and Page limit
//   /post                                      // Add a post with parameter title and body
//   /post                                      // Modify a post with parameter title and body and id
//   /post/1                                    // Delete a post by id
$router->get('/posts/:string?', 'Posts@listPosts');
$router->get('/post/:id', 'Posts@getPost');
$router->get('/posts_by_user/:id/:string?', 'Posts@listPostsByUser');
$router->post('/post', 'Posts@addPost');
$router->put('/post', 'Posts@editPost');
$router->delete('/post/:id', 'Posts@deletePost');



// Available on Comments
//   /comments

//   /comment/1                                    // Get Post By ID 
//   /comments?page=2                              // Adding Page Number 
//   /comments?page=2&limit=5                      // Adding Page Number and Page limit
//   /comments/desc                                // List in descending order
//   /comments/desc?page=2&limit=5                 // List in descending order Adding Page Number and Page limit
//   /comments_by_post/postId                      // List by user
//   /comments_by_post/postId/desc                 // List by userin descending order
//   /comments_by_post/postId/desc?page=2&limit=5  // List by user in descending order Adding Page Number and Page limit
//   /comment                                       // Add a comment with parameter name, email and body
//   /comment                                       // Modify a comment with parameter name, email, body and id
//   /comment/1                                     // Delete a comment by id
$router->get('/comments/:string?', 'Comments@listComments');
$router->get('/comment/:id', 'Comments@getComment');
$router->get('/comments_by_post/:id/:string?', 'Comments@listCommentsByPost');
$router->post('/comment', 'Comments@addComment');
$router->put('/comment', 'Comments@editComment');
$router->delete('/comment/:id', 'Comments@deleteComment');






// Available on Albums
//   /albums

//   /album/1                                    // Get Post By ID 
//   /albums?page=2                              // Adding Page Number 
//   /albums?page=2&limit=5                      // Adding Page Number and Page limit
//   /albums/desc                                // List in descending order
//   /albums/desc?page=2&limit=5                 // List in descending order Adding Page Number and Page limit
//   /albums_by_user/userId                      // List by user
//   /albums_by_user/userId/desc                 // List by user in descending order
//   /albums_by_user/userId/desc?page=2&limit=5  // List by user in descending order Adding Page Number and Page limit
//   /album                                  // Add a album with parameter title
//   /album                                 // Modify a album with parameter title and id
//   /album/1                                // Delete a album by id
$router->get('/albums/:string?', 'Albums@listAlbums');
$router->get('/album/:id', 'Albums@getAlbum');
$router->get('/albums_by_user/:id/:string?', 'Albums@listAlbumsByUser');
$router->post('/album', 'Albums@addAlbum');
$router->put('/album', 'Albums@editAlbum');
$router->delete('/album/:id', 'Albums@deleteAlbum');




// Available on Todos
//   /todos

//   /todo/1                                    // Get Post By ID 
//   /todos?page=2                              // Adding Page Number 
//   /todos?page=2&limit=5                      // Adding Page Number and Page limit
//   /todos/desc                                // List in descending order
//   /todos/desc?page=2&limit=5                 // List in descending order Adding Page Number and Page limit
//   /todos_by_user/userId                      // List by user
//   /todos_by_user/userId/desc                 // List by user in descending order
//   /todos_by_user/userId/desc?page=2&limit=5  // List by user in descending order Adding Page Number and Page limit
//   /todo                                  // Add a todo with parameter title and completed(bool)
//   /todo                                 // Modify a todo with parameter title and completed(bool) and id
//   /todo/1                                // Delete a todo by id
$router->get('/todos/:string?', 'Todos@listTodos');
$router->get('/todo/:id', 'Todos@getTodo');
$router->get('/todos_by_user/:id/:string?', 'Todos@listTodosByUser');
$router->post('/todo', 'Todos@addTodo');
$router->put('/todo', 'Todos@editTodo');
$router->delete('/todo/:id', 'Todos@deleteTodo');





// Available on Photos
//   /photos

//   /photo/1                                       // Get Post By ID 
//   /photos?page=2                                 // Adding Page Number 
//   /photos?page=2&limit=5                         // Adding Page Number and Page limit
//   /photos/desc                                   // List in descending order
//   /photos/desc?page=2&limit=5                    // List in descending order Adding Page Number and Page limit
//   /photos_by_album/albumId                       // List by album
//   /photos_by_album/albumId/desc                  // List by album in descending order
//   /photos_by_album/albumId/desc?page=2&limit=5   // List by album in descending order Adding Page Number and Page limit
//   /photo                                     // Add a photo with parameter title, url and thumbnail
//   /photo                                    // Modify a photo with parameter title, url and thumbnail and id
//   /photo/1                                   // Delete a photo by id
$router->get('/photos/:string?', 'Photos@listPhotos');
$router->get('/photo/:id', 'Photos@getPhoto');
$router->get('/photos_by_album/:id/:string?', 'Photos@listPhotosByAlbum');
$router->post('/photo', 'Photos@addPhoto');
$router->put('/photo', 'Photos@editPhoto');
$router->delete('/photo/:id', 'Photos@deletePhoto');



// Available on Users
//   /users

//   /user/1                                       // Get Post By ID 
//   /users?page=2                                 // Adding Page Number 
//   /users?page=2&limit=5                         // Adding Page Number and Page limit
//   /users/desc                                   // List in descending order
//   /users/desc?page=2&limit=5                    // List in descending order Adding Page Number and Page limit
//   /user_by_email/name@mail.com                  // List by album
//   /user_by_username/username1                  // List by album
//   /user                                     // Add a user with parameter title, url and thumbnail
//   /user                                    // Modify a user with parameter title, url and thumbnail and id
//   /user/1                                   // Delete a user by id

$router->get('/users/:string?', 'Users@listUsers');
$router->get('/user/:id', 'Users@getUser');
$router->get('/user_by_email/:any', 'Users@getUserByEmail');
$router->get('/user_by_username/:string', 'Users@getUserByUsername');
$router->post('/user', 'Users@addUser');
$router->put('/user', 'Users@editUser');
$router->delete('/user/:id', 'Users@deleteUser');


// referesh all data from original file
$router->get('/refresh_all', function(){
    $helper = new Helper;
    $helper->refreshAllDataFromOriginal();
});


  $router->error(function() {
    // if it is ajax request
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'){
        return json_encode(['error'=>'The page you are requesting can not be found']);
    }
  });

$router->run();