<?php
	namespace App\Controller;

	use App\Entity\Article;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Request;

	use Symfony\Component\Routing\Annotation\Route;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;


	class ArticleController extends Controller{

		/**
		 * @Route("/", name="article_list")
		 * @Method({"GET"})
		 */

		public function index(){
			
		//Call a function that returns all data from Article table.

		$articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

		//This function is called from the index page. Pass the data to the view. (index.html.twig)

		return $this->render('articles/index.html.twig', array('articles' => $articles));
		}


		/**
		* @Route("/article/new", name="new_article")
		* Method({"GET", "POST"})
		*/

		public function new(Request $request){

			$article = new Article();

			$form = $this->createFormBuilder($article)
			->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
			->add('body', TextareaType::class, array('required' => false, 'attr' => array('class' => 'form-control')))
			->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
			->getForm();

			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()){
				$article = $form->getData();

				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($article);
				$entityManager->flush();

				return $this->redirectToRoute('article_list');
			}

			return $this->render('articles/new.html.twig', array(
				'form' => $form->createView()
			));

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