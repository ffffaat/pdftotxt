import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;

import java.io.File;
import java.io.IOException;

public class PDFToTextConverter {

    public static void main(String[] args) {
        if (args.length != 1) {
            System.out.println("Please provide the path to the PDF file.");
            return;
        }

        String pdfFilePath = args[0];
        convertPDFToText(pdfFilePath);
    }

    private static void convertPDFToText(String pdfFilePath) {
        try {
            File pdfFile = new File(pdfFilePath);
            PDDocument document = PDDocument.load(pdfFile);
            PDFTextStripper pdfTextStripper = new PDFTextStripper();
            String text = pdfTextStripper.getText(document);
            System.out.println("Text from file: " + pdfFile.getName());
            System.out.println(text);
            document.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
