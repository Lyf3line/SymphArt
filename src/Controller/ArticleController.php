<?php
	namespace App\Controller;

	use App\Entity\Article;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;


	class ArticleController extends Controller{

		/*
		@Route("/", name="article_list")
		@Method({"GET"})
		*/

		public function index(){
			
		//Call a function that returns all data from Article table.

		$articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

		//This function is called from the index page. Pass the data to the view. (index.html.twig)

		return $this->render('articles/index.html.twig', array('articles' => $articles));
		}

		/**
		* @Route("/article/{id}", name="article_show")
		*/

		public function show($id){

		// Call function to show a specific article by id.

		$article = $this->getDoctrine()->getRepository(Article::class)->find($id);

		//Get the results and pass them to the view. In the form of an array.
		return $this->render('articles/show.html.twig', array('article' => $article));
		}

		/**
		 * @Route("/article/save")
		 */

		public function save(){
			$entityManager = $this->getDoctrine()->getManager();

			// Just to see how it works, not actually a phase of the application.

			$article = new Article();
			$article->setTitle('Article One');
			$article->setBody('This is the body for article one');

			$entityManager->persist($article);
			$entityManager->flush();

			return new Response('Saves an article with the id of '.$article->getId());

		}
	}