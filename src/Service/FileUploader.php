<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private SluggerInterface $slugger;
    private string $uploadsDirectory;

    public function __construct(SluggerInterface $slugger,string $uploadsDirectory)
    {
        $this->slugger = $slugger;
        $this->uploadsDirectory = $uploadsDirectory;
    }

    /**
     * Télécharge un fichier et génere son chemin
     *
     * @param UploadedFile $file le fichier téléchargé.
     * @return array{fileName: string, filePath: string}
     */
    public function upload(UploadedFile $file): array
    {
        $fileName = $this->generateUniqFileName($file);

        try {
            $file->move($this->uploadsDirectory, $fileName);
        } catch (FileException $fileException){
            throw $fileException;
        }

        return [
            'fileName' => $fileName,
            'filePath' => $this->uploadsDirectory . $fileName
        ];
    }

    /**
     * Génere un unique nom de fichier pour les fichiers téléchargés
     *
     * @param UploadedFile $file le fichier téléchargé.
     * @return string l'unique filename slugged.
     */
    public function generateUniqFileName(UploadedFile $file): string
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $originalFileNameSlugged = $this->slugger->slug(strtolower($originalFileName));
        $randomId = uniqid();

        return "{$originalFileNameSlugged}-{$randomId}.{$file->guessExtension()}";
    }
}