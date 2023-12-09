<?php
function sanitizeFilename($filename) {
    // Replace spaces with underscores and remove other potentially problematic characters
    return preg_replace("/[^a-zA-Z0-9_.-]/", "_", str_replace(" ", "_", $filename));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = "C:\\Users\\user\\Downloads\\uploads"; // Absolute path

    // Sanitize the PDF file name
    $pdfFile = $uploadDir . sanitizeFilename(basename($_FILES["pdfFile"]["name"]));

    // Ensure the directory exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Check if the directory is writable
    if (!is_writable($uploadDir)) {
        die("Error: The directory is not writable.");
    }

    if (move_uploaded_file($_FILES["pdfFile"]["tmp_name"], $pdfFile)) {
        // Use PDFBox to convert PDF to Text
        // Assuming the compiled Java class is in the same directory as the PHP script
        $javaCommand = 'java -cp "C:\Users\user\IdeaProjects\try2\src\;pdfbox-app-2.0.26.jar" PDFToTextConverter ' . escapeshellarg($pdfFile) . ' 2>&1';
        exec($javaCommand, $output);


        // Save the converted text to a file
        $textFile = $uploadDir . "output.txt";
        file_put_contents($textFile, implode("\n", $output));

        // Remove uploaded PDF file
        unlink($pdfFile);

        // Provide the converted text file for download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="output.txt"');
        readfile($textFile);

        // Remove the text file after download
        unlink($textFile);

        exit(); // Stop further execution
    } else {
        echo "Error uploading file.";
    }
    // Remove uploaded PDF file
    unlink($pdfFile);

    // Remove the text file after download
    unlink($textFile);

}
?>
