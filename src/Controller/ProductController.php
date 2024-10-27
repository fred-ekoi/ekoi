<?php
namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Route("/products", name="product_list")
     */
    public function list(): Response
    {
        $products = $this->productService->getProducts();

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/{code}", name="product_show")
     */
    public function show(string $code): Response
    {
        $product = $this->productService->getProduct($code);
        $product['description'] = $this->productService->getProductDescription($product['id']);
        $product['features'] = $this->productService->getProductFeatures($product['id']);
        dump($product['features']);

        if (!$product) {
            throw $this->createNotFoundException('The product does not exist');
        }

        $variants = $this->productService->getVariants($product);
        $defaultVariant = $this->productService->getVariant($product['defaultVariant']);

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'variants' => $variants,
            'defaultVariant' => $defaultVariant,
        ]);
    }

    /**
     * @Route("/product/index", name="product_index")
     */
    public function productReference(): Response
    {
        return $this->render('product/index.html.twig');
    }
}
