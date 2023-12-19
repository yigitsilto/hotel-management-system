<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\RoomCreateRequest;
use App\Http\Requests\admin\RoomUpdateRequest;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\MediaCannotBeDeleted;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Hotel $hotel)
    {
        dd($hotel->name);
    }

    /**
     * @throws MediaCannotBeDeleted
     */
    public function deleteImages(Room $room, Media $media)
    {
        $room->deleteMedia($media);
        return redirect()->back()->with('success', 'Başarıyla silindi.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Hotel $hotel)
    {
        return view('admin.roomManagement.create', compact('hotel'));
    }

    public function uploadImages(Request $request, Room $room)
    {

        if ($request->hasFile('file')) {
            $room->addMedia($request->file('file'))
                 ->toMediaCollection('room_images');
        }

        return response()->json([
                                    'success' => 'Resim başarıyla yüklendi.'
                                ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomCreateRequest $request, Hotel $hotel)
    {

        $room = new Room([
                             'hotel_id' => $hotel->id,
                             'title' => $request->title,
                             'base_image' => $request->base_image,
                             'room_type' => $request->room_type,
                             'capacity' => $request->capacity,
                             'description' => $request->description,
                             'price' => $request->price,
                             'is_available' => $request->is_available,
                         ]);

        $this->createImage($request,$room, $hotel->name);
        $room->save();

        // Başarıyla kaydedildiğine dair bir mesaj veya yönlendirme ekleme
        return redirect()
        ->route('room-management.upload.images.create', ['room' => $room])
        ->with([
                   'success' => 'Oda başarıyla oluşturuldu. Şimdi odaya ait resimleri yükleyebilirsiniz.'
               ]);
    }

    private function createImage(Request $request, Room $room, string $hotelName): void
    {

        // Eğer dosya yüklendi ise
        if ($request->hasFile('image')) {
            // Dosyanın adını belirle
            $imageName = time() . '.' . $request->file('image')
                                                ->getClientOriginalExtension();

            // Dosyayı public/hotel_images dizinine taşı
            $request->file('image')
                    ->move(public_path($hotelName .'/room_images'), $imageName);

            // Otel modeline dosyanın yolu ekleniyor
            $room->base_image = "/".$hotelName.'/room_images/' . $imageName;
        }
    }

    public function uploadImagesCreate(Room $room)
    {
        $medias = $room->getMedia('room_images');
        return view('admin.roomManagement.upload-images', compact('room', 'medias'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return view('admin.roomManagement.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomUpdateRequest $request, Room $room)
    {
        $validated = $request->validated();

        $room->title = $validated['title'];
        $room->room_type = $validated['room_type'];
        $room->capacity = $validated['capacity'];
        $room->description = $validated['description'];
        $room->price = $validated['price'];
        $room->is_available = $validated['is_available'];

        if ($request->hasFile('image')) {
            $this->createImage($request, $room, $room->hotel->name);
        }

        $room->save();

        return redirect()
        ->route('room-management.upload.images.create', ['room' => $room])
        ->with([
                   'success' => 'Oda başarıyla güncellendi. Şimdi odaya ait resimleri düzenleyebilirsiniz.'
               ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //
    }
}
