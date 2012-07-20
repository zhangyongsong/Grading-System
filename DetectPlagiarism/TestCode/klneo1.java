import java.util.Scanner;
public class Horizontal {
     public static int[] grades;
    public static int[] bins = new int[10];
    
    public static void readGrades(){
        Scanner in = new Scanner(System.in);
        int numStudents = in.nextInt();
        grades = new int[numStudents];
        for(int i=0; i<grades.length; i++){
            grades[i] = in.nextInt();
        }
    }
    public static void computeHistogram(){
        for(int i=0; i<bins.length; i++){
            bins[i]=0;
        }
        for(int gradeNum=0; gradeNum<grades.length; gradeNum++){
            int binNum;
            if(grades[gradeNum] < 100){
                binNum = grades[gradeNum]/10;
            }else{
                binNum=9;
            }
            bins[binNum]++; //increment count by this bin
        }
    }
    
    public static void printHorizontal(){
        for(int i=0; i<bins.length; i++){
            //find the lowerbound and upperbound of each bits
            int lowerbound = i*10;
            int upperbound = i*10+9;
            if(i==bins.length -1){
                upperbound =100;
            }
            //%3d: integer with 3 spaces
            System.out.printf("%3d -%3d: ", lowerbound, upperbound);
            
            int numStars = bins[i];
            for(int count=0; count < numStars; count++){
                System.out.print("*");
            }
            System.out.println();
        }
        System.out.println();
    }
    
    public static void main(String[] args){
        readGrades();
        computeHistogram();
        printHorizontal();
    }
}
