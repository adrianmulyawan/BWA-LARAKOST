<?php

namespace App\Http\Controllers;

use App\Interfaces\BoardingHouseRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\CityRepositoryInterface;
use Illuminate\Http\Request;

class BoardingHouseController extends Controller
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

    public function find()
    {
        $cities = $this->cityRepository->getAllCities();
        $categories = $this->categoryRepository->getAllCategories();

        return view('pages.boarding_house.find-boarding-house', compact(
            'cities',
            'categories'
        ));
    }

    public function findResult(Request $request)
    {
        $boardingName = $request['name'];
        $city = $request['city'];
        $category = $request['category'];

        $items = $this->boadrdingHouseRepository->getAllBoardingHouses($boardingName, $city, $category);
        // dd($items);

        return view('pages.boarding_house.index', compact('items'));
    }
}
