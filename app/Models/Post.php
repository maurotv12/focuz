<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Follower;
use App\Models\Like;
use App\Models\Model;
use App\Models\User;

class Post extends Model
{
  protected $table = 'posts';

  public function getFollowedUsersPosts($user_id)
  {
    $followerModel = new Follower();
    $commentModel = new Comment();
    $likeModel = new Like();
    $userModel = new User();

    // Obtener los IDs de los usuarios seguidos por el usuario actual
    $followedUsers = $followerModel->where('user_follower_id', $user_id)->get();

    // Extraer los IDs de los usuarios seguidos
    $followedUserIds = array_map(function ($follower) {
      return $follower['user_followed_id'];
    }, $followedUsers);

    if (empty($followedUserIds)) {
      return []; // Si no sigue a nadie, devolver un array vacío
    }

    $posts = $this->where('user_id', 'IN', $followedUserIds)->orderBy('created_at', 'DESC')->get();

    // Agregar información adicional a cada post
    $posts = array_map(function ($post) use ($commentModel, $likeModel, $userModel) {
      $post['comments'] = $commentModel->getComments($post['id']);
      $post['comment_count'] = count($post['comments']);
      $post['likes'] = $likeModel->getLikes($post['id']);
      $post['like_count'] = count($post['likes']);
      $post['user'] = $userModel->getUser($post['user_id']);
      $post['is_blurred'] = (bool) $post['is_blurred'];
      return $post;
    }, $posts);

    return $posts;
  }

  public function getPostsByUser($user_id)
  {
    $postModel = new Post();
    $userModel = new User();
    $commentModel = new Comment();
    $likeModel = new Like();

    $posts = $postModel->where('user_id', $user_id)->get();

    $posts = array_map(function ($post) use ($userModel, $commentModel, $likeModel) {
      $post['comments'] = $commentModel->getComments($post['id']);
      $post['comment_count'] = count($post['comments']);
      $post['likes'] = $likeModel->getLikes($post['id']);
      $post['like_count'] = count($post['likes']);
      $post['user'] = $userModel->getUser($post['user_id']);
      $post['is_blurred'] = (bool) $post['is_blurred'];

      return $post;
    }, $posts);

    usort($posts, function ($a, $b) {
      return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    return $posts;
  }

  public function getPost($post_id)
  {
    $post = $this->find($post_id);

    if (!$post) {
      return null; // Si no se encuentra el post, devolver null
    }

    $commentModel = new Comment();
    $likeModel = new Like();
    $userModel = new User();

    // Agregar información adicional al post
    $post['comments'] = $commentModel->getComments($post_id);
    $post['comment_count'] = count($post['comments']);
    $post['likes'] = $likeModel->getLikes($post_id);
    $post['like_count'] = count($post['likes']);
    $post['user'] = $userModel->getUser($post['user_id']);
    $post['is_blurred'] = (bool) $post['is_blurred'];

    return $post;
  }

  public function toggleBlur($post_id, $is_blurred)
  {
    // Usar where y update del modelo base
    $this->where('id', $post_id)->update($post_id, [
      'is_blurred' => $is_blurred
    ]);
  }

  // public function toggleBlur($post_id, $is_blurred)
  // {
  //  
  //   $sql = "UPDATE posts SET is_blurred = ? WHERE id = ?";
  //   $this->query($sql, [$is_blurred, $post_id]);
  // }

}
