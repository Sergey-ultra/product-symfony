<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Request\CalculatePriceRequest;
use App\Request\PaymentRequest;
use App\Service\CalculatePriceService\ICalculatePrice;
use App\Service\PaymentService\PaymentFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ProductController  extends AbstractController
{
    #[Route('/calculate-price', name: 'calculate-price', methods:['post'] )]
    public function calculatePrice(
        ICalculatePrice $calculatePriceService,
        ProductRepository     $productRepository,
        CalculatePriceRequest         $request): JsonResponse
    {
        $product = $productRepository->find($request->getProduct());

        if (!$product) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        $price = $calculatePriceService->calculate(
            $product->getPrice(),
            $request->getTaxNumber(),
            $request->getCouponCode()
        );

        return $this->json(['data' => $price]);
    }

    #[Route('/pay-product', name: 'pay-product', methods:['post'] )]
    public function payProduct(
        ICalculatePrice $calculatePriceService,
        PaymentFactory $paymentFactory,
        ProductRepository $productRepository,
        PaymentRequest $request
    )
    {
        $product = $productRepository->find($request->getProduct());

        if (!$product) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        $price = $calculatePriceService->calculate(
            $product->getPrice(),
            $request->getTaxNumber(),
            $request->getCouponCode()
        );

        try {
            $paymentProcessor = $paymentFactory::factory($request->getPaymentProcessor());
            $paymentProcessor->process($price);
            return $this->json(['data' => ['status' => 'success']]);
        } catch (\Exception $e) {
            return $this->json($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
