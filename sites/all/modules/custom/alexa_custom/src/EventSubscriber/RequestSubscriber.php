<?php
/**
 * Created by PhpStorm.
 * User: nayana.rane
 * Date: 2018-12-25
 * Time: 07:50
 */

namespace Drupal\alexa_custom\EventSubscriber;

use Drupal\alexa\AlexaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * An event subscriber for Alexa request events.
 */
class RequestSubscriber implements EventSubscriberInterface {

  /**
   * Gets the event.
   */
  public static function getSubscribedEvents() {
    $events['alexaevent.request'][] = ['onRequest', 0];
    return $events;
  }
  /**
   * Called upon a request event.
   *
   * @param \Drupal\alexa\AlexaEvent $event
   *   The event object.
   */
  public function onRequest(AlexaEvent $event) {
    $request = $event->getRequest();
    $response = $event->getResponse();

    switch ($request->intentName) {
      case 'AMAZON.HelpIntent':
        $response->respond('You can ask "what are the latest rhymes" and I will read the titles to you');
        break;

      case 'latestStory':
        $latestArticles = $this->current_posts_contents();
        $response->respond($latestArticles);
        break;

      case 'readStory':
        $article = $request->getSlot('story');
        $articleResponse = $this->current_post_body($article);
        //echo json_encode($response);
        if(is_array($articleResponse)) {
          $response->addAudio($articleResponse);
        }
        else {
          $response->respond($articleResponse);
        }
        break;

      default:
        $response->respond('Hello User. I can tell you the latest stories.');
        break;
    }
  }

  /**
   * Get latest articles function.
   *
   * Set beginning and end dates, retrieve posts from database
   * saved in that time period.
   *
   * @return array
   *   A result set of the targeted posts.
   */
  function current_posts_contents() {
    $query = \Drupal::entityQuery('node');
    // Return the newest 5 articles.
    $newest_articles = $query->condition('type', 'stories_and_rhymes')
      ->condition('status', 1)
      ->sort('created', 'DESC')
      ->pager(5);

    $nids = $query->execute();

    $fullstring = 'Here are the five latest stories from Drupal application, ';

    foreach ($nids as $nid) {
      $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
      $name = $node->getTitle();
      $fullstring = $fullstring . ' ' . $name . ',';
    }

    $fullstring = $fullstring . '. You can have me read you one of the story by saying, Alexa read, and then the title of the story.';

    return($fullstring);
  }

  /**
   * Get body of an article.
   *
   * Get body of article based on title.
   *
   * @return array
   *   A result set of the targeted posts.
   */
  function current_post_body(&$article) {

    $query = \Drupal::entityQuery('node');
    // Return the newest 5 articles.
    $group = $query
      ->orConditionGroup()
      ->condition('title', $article, 'CONTAINS')
      ->condition('field_search_phrases', $article, 'CONTAINS');
    $article_query = $query->condition('type', 'stories_and_rhymes')
      ->condition('status', 1)
      ->condition($group)
      ->sort('created', 'DESC')
      ->pager(1);

    $nids = $query->execute();

    if ($nids) {
      foreach ($nids as $nid) {
        $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
        $name = $node->getTitle();
        $fileUrl = file_create_url($node->get('field_story_audio_file')->entity->uri->value);
        $response['title'] = $name;
        $response['url'] = $fileUrl;
      }
      //$response = $response . 'Here is the article, ' . $name . '. ' . $foo;
    }
    else {
      $response = 'I do not see an story by that name. To have me list the latest 5 stories, say, alexa what are the latest stories.';
    }
    //return "https://alexad8xyrypcjuab.devcloud.acquia-sites.com/sites/default/files/2018-12/Landga_Aala_Re.mp3";
    return($response);
  }
}