<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelCreateRequest;
use App\Http\Requests\HotelUpdateRequest;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function index(): View
    {
        $hotels = Hotel::query()
                       ->orderBy('updated_at', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->get();
        return view('admin.hotelManagement.index', compact('hotels'));
    }

    public function create(): View
    {
        return view('admin.hotelManagement.create');
    }

    public function store(HotelCreateRequest $request): \Illuminate\Http\RedirectResponse
    {

        $validatedData = $request->validated();

        $hotel = new Hotel([
                               'name' => $validatedData['name'],
                               'location' => $validatedData['location'],
                               'description' => $validatedData['description'],
                               'total_rooms' => $validatedData['total_rooms'],
                               'contact_email' => $validatedData['contact_email'],
                               'contact_phone' => $validatedData['contact_phone'],
                               'is_available' => $validatedData['is_available'],
                           ]);

        $this->createImage($request, $hotel);
        $hotel->save();


        // Başarıyla kaydedildiğine dair bir mesaj veya yönlendirme ekleme
        return redirect()
            ->route('hotel-management.index')
            ->with('success', 'Otel başarıyla eklendi.');
    }

    private function createImage(Request $request, Hotel $hotel): void
    {

        // Eğer dosya yüklendi ise
        if ($request->hasFile('image')) {
            // Dosyanın adını belirle
            $imageName = time() . '.' . $request->file('image')
                                                ->getClientOriginalExtension();

            // Dosyayı public/hotel_images dizinine taşı
            $request->file('image')
                    ->move(public_path('hotel_images'), $imageName);

            // Otel modeline dosyanın yolu ekleniyor
            $hotel->image = '/hotel_images/' . $imageName;
        }
    }


    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);

        return view('admin.hotelManagement.edit', compact('hotel'));
    }

    public function update(HotelUpdateRequest $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $validatedData = $request->validated();

        $hotel->name = $validatedData['name'];
        $hotel->location = $validatedData['location'];
        $hotel->description = $validatedData['description'];
        $hotel->total_rooms = $validatedData['total_rooms'];
        $hotel->contact_email = $validatedData['contact_email'];
        $hotel->contact_phone = $validatedData['contact_phone'];
        $hotel->is_available = $validatedData['is_available'];

        $this->createImage($request, $hotel);
        $hotel->save();

        return redirect()
            ->route('hotel-management.index')
            ->with('success', 'Otel bilgisi başarıyla güncellendi.');

    }

    public function show($id)
    {
        $hotel = Hotel::findOrFail($id);

        return view('admin.hotelManagement.show', compact('hotel'));
    }

    // TODO silme yapılacak ama odası varsa silinemez sadece pasif yapılabilri kontrolleri eklendikten sonra yapılacak
}
