<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private string $stationDirectory;
    private string $carTargetDirectory;
    private SluggerInterface $slugger;

    public function __construct(
        string $stationDirectory,
        string $carTargetDirectory,
        SluggerInterface $slugger,
    ) {
        $this->stationDirectory = $stationDirectory;
        $this->carTargetDirectory = $carTargetDirectory;
        $this->slugger = $slugger;
    }

    public function uploadStations(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '.csv';

        try {
            $file->move($this->getStationTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function uploadCars(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '.csv';

        try {
            $file->move($this->getCarTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getCarTargetDirectory(): string
    {
        return $this->carTargetDirectory;
    }
    public function getStationTargetDirectory(): string
    {
        return $this->stationDirectory;
    }
}
