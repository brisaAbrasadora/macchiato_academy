<?php
namespace macchiato_academy\app\utils;

use macchiato_academy\app\exceptions\FileException;

class File
{
    private $file;
    private $fileName;

    /**
     * @param string $fileName
     * @param array $arrTypes
     * @throws FileException
     */
    public function __construct(string $fileName, array $arrTypes)
    {
        $this->file = $_FILES[$fileName];
        $this->fileName = "";

        if (!isset($this->file) || $this->file['size'] == 0) {
            throw new FileException('You must select a file');
        }

        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            switch ($this->file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new FileException('File is too big');
                    break;
                case UPLOAD_ERR_PARTIAL:
                    throw new FileException('Couldn\'t upload the complete file');
                    break;
                default:
                    throw new FileException('Couldn\'t upload the file');
                    break;
            }
        }

        if (in_array($this->file['type'], $arrTypes) === false) {
            throw new FileException('Type file not supported');
        }
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $destiny
     * @return void
     * @throws FileException
     */
    public function saveUploadFile(string $destiny)
    {
        // Primero hay que comprobar que el fichero se ha subido desde el formulario.
        // Hay un tipo de ataques que intenta acceder a archivos del SO
        if (is_uploaded_file($this->file['tmp_name']) === false)
            throw new FileException('File has not been uploaded from a form');
        $this->fileName = $this->file['name'];
        // Vamos a crear la variable path y le asignamos la path completa donde se alojara nuestro fichero
        $path = $_SERVER['DOCUMENT_ROOT'] . $destiny . $this->fileName;
        // Comprobamos si ya existe el fichero con ese nombre
        if (is_file($path) === true) {
            // Generamos un nombre aleatorio
            $idUnico = time();
            $this->fileName = $idUnico . "_" . $this->fileName;
            // Modificamos el contenido de la variable para que contenga ahora el nuevo nombre
            $path = $_SERVER['DOCUMENT_ROOT'] . $destiny . $this->fileName; 
        }
        if (
            move_uploaded_file($this->file['tmp_name'], $path) ===
            false
            // Esta función requiere que le pasemos por el segundo parámetro la path completa donde se va
            // alojar la imagen, incluyendo el nombre del archivo.
        )
            throw new FileException('Couldn\'t move the file to its destiny');
    }
}