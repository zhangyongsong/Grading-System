/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
import java.io.*;
/**
 *
 * @author YONGSONG
 */
public class File2String {
    private File inputFile;
    private String fileContent;
    private String output;

    public File2String(String filePath){
        this.inputFile = new File(filePath);
    }

    public void readFile() throws IOException{
        StringBuilder sb = new StringBuilder("");
        int in;
        BufferedReader bReader = new BufferedReader(new FileReader(inputFile));

        while((in=bReader.read())!= -1){
            sb.append((char)in);
        }
        fileContent = sb.toString();
        bReader.close();
    }

    public void process(){
        CommentRemover cRemover = new CommentRemover(fileContent);
        cRemover.removeComment();
        output = cRemover.getRemovedString();
    }

    public String getOutput(){
        return output;
    }
private static String parentDir = "../";
    private static String newLine = System.getProperty("line.separator");
    public static void main(String[] args) throws IOException{
        File2String fxy = new File2String("E:/PROGRAMMING/Java_Programs/Exercises/RomanNumeral.java");
        fxy.readFile();
        fxy.process();
        PrintWriter pw = new PrintWriter(new BufferedWriter(new FileWriter
                ("E:/Program Files/WampServer/www/Remove Comment/abc.java")));
        pw.println(fxy.fileContent+ "\n\n"+ fxy.getOutput());
        pw.close();
        return;
    }

}
