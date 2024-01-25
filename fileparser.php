<?php
class File {

    use fileHelper;

    private $contents = [];

    private $file;

    public function __construct(public string $filePath) {}

    public function openFile(): self {
        $this->file = fopen($this->filePath, 'r');
        
        if ($this->file === false) {
            throw new Exception('Unable to open file');
        }

        return $this;
    }

    public function readFile() : self 
    {
        while (($line = fgets($this->file)) !== false) {
            $this->contents[] = $line;
        }
        
        fclose($this->file);
        return $this;
    }

    public function outputShortestStringInLine(): void
    {
        $this->shortestStringInLine($this->contents);
    }

}

trait fileHelper {
    public function shortestStringInLine(array $content): void
    {    
        for ($i = 0; $i < count($content); $i++) {
            $shortest = null;
            $strings = explode(' ', $content[$i]);
            foreach ($strings as $string) {
                if ($shortest === null || strlen($string) < strlen($shortest)) {
                    $shortest = $string;
                }
            }
            echo $i+1 .". ". $shortest. "\n";
        }
    }
}

try{
    $arguementsCount = count($argv);

    if ($arguementsCount < 1 and $arguementsCount > 2) {
        throw new Exception("Invalid Arguement");
    }

    $filepath = "./".$argv[1];

    $fileObject = new File($filepath);

    $fileObject->openFile()->readFile()->outputShortestStringInLine();

} catch (Throwable $e) {
    echo $e->getMessage();
}
