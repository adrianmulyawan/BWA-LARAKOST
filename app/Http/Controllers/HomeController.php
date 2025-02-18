<?php

namespace App\Http\Controllers;

use App\Interfaces\BoardingHouseRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\CityRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private CityRepositoryInterface $cityRepository;
    private CategoryRepositoryInterface $categoryRepository;
    private BoardingHouseRepositoryInterface $boadrdingHouseRepository;

    public function __construct(
        CityRepositoryInterface $cityRepository,
        CategoryRepositoryInterface $categoryRepository,
        BoardingHouseRepositoryInterface $boardingHouseRepository
    ) {
        $this->cityRepository = $cityRepository;
        $this->categoryRepository = $categoryRepository;
        $this->boadrdingHouseRepository = $boardingHouseRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->getAllCategories();

        $popularBoardingHouses = $this->boadrdingHouseRepository->getPopularBoardingHouses();

        $cities = $this->cityRepository->getAllCities();

        $boardingHouses = $this->boadrdingHouseRepository->getAllBoardingHouses();

        return view('pages.home', compact(
            'categories',
            'popularBoardingHouses',
            'cities',
            'boardingHouses'
        ));
    }
}
