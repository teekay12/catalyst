<?php

interface FileSystemInterface {

    public function getName();
}

class File implements FileSystemInterface {

    public function __construct(public string $name, public string $content) {}

    public function getContent(): string {
        return $this->content;
    }

    public function setContent($content): void {
        $this->content = $content;
    }

    public function getName(): string {
        return $this->name;
    }
}

class CustomDirectory implements FileSystemInterface {
    private $contents = [];

    public function __construct(public string $name) {
        $this->contents = [];
    }

    public function addItem(FileSystemInterface $item): void {
        $this->contents[$item->getName()] = $item;
    }

    public function removeItem($itemName): void {
        if (isset($this->contents[$itemName])) {
            unset($this->contents[$itemName]);
        }
    }

    public function getItem($itemName): mixed {
        return $this->contents[$itemName] ?? null;
    }

    public function getContents(): array {
        return $this->contents;
    }

    public function getName(): string {
        return $this->name;
    }
}

class FileSystem {
    private $rootCustomDirectory;

    public function __construct() {
        $this->rootCustomDirectory = new CustomDirectory("root");
    }

    public function getRootCustomDirectory(): CustomDirectory {
        return $this->rootCustomDirectory;
    }
}

$fileSystem = new FileSystem();

$rootCustomDirectory = $fileSystem->getRootCustomDirectory();

$file1 = new File("file1.txt", "Content of file1");
$file2 = new File("file2.txt", "Content of file2");

$dir1 = new CustomDirectory("dir1");
$dir1->addItem($file1);
$dir1->addItem($file2);

$dir2 = new CustomDirectory("dir2");
$dir2->addItem($file2);

$rootCustomDirectory->addItem($dir1);
$rootCustomDirectory->addItem($dir2);

echo (($rootCustomDirectory->getItem("dir1"))->getItem("file1.txt"))->getContent()."\n";

// Modify file content
(($rootCustomDirectory->getItem("dir1"))->getItem("file1.txt"))->setContent("New content")."\n";

// Access modified content
echo (($rootCustomDirectory->getItem("dir1"))->getItem("file1.txt"))->getContent()."\n";

//print file in directory 1
print_r(($rootCustomDirectory->getItem("dir1"))->getContents());

//remove file1.txt from directory 1
($rootCustomDirectory->getItem("dir1"))->removeItem("file1.txt");

//print file in directory 1 after removing file
print_r(($rootCustomDirectory->getItem("dir1"))->getContents());
