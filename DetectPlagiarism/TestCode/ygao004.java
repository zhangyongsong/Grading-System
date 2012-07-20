/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author ygao004
 */
import java.util.Scanner;
public class PrintHorizontalHistogram {

    /**
     * @param args the command line arguments
     */
   
    public static void main(String[] args) {
        
        // TODO code application logic here
    int number;
    int[] grades;
    Scanner in=new Scanner(System.in);
    number=in.nextInt();
    grades=new int[number];
    int num0to10=0;
    int num10to20=0;
    int num20to30=0;
    int num30to40=0;
    int num40to50=0;
    int num50to60=0;
    int num60to70=0;
    int num70to80=0;
    int num80to90=0;
    int num90to100=0;
    for(int i=0;i<number;i++)
        {
            grades[i]=in.nextInt();
            if (grades[i]<10&&grades[i]>=0)
                num0to10++;
            if(grades[i]<20&&grades[i]>=10)
                num10to20++;
            if(grades[i]<30&&grades[i]>=20)
                num20to30++;
            if(grades[i]<40&&grades[i]>=30)
                num30to40++;
            if(grades[i]<50&&grades[i]>=40)
                num40to50++;
            if(grades[i]<60&&grades[i]>=50)
                num50to60++;
            if(grades[i]<70&&grades[i]>=60)
                num60to70++;
            if(grades[i]<80&&grades[i]>=70)
                num70to80++;
            if(grades[i]<90&&grades[i]>=80)
                num80to90++;
            if(grades[i]<=100&&grades[i]>=90)
                num90to100++;
            
        }

    
    System.out.print("  0 -  9: ");
    for (int i=0;i<num0to10;i++)
        System.out.print("*");
    System.out.println();
    
    System.out.print(" 10 - 19: ");
    for (int i=0;i<num10to20;i++)
        System.out.print("*");
    System.out.println();
    
    System.out.print(" 20 - 29: ");
    for (int i=0;i<num20to30;i++)
        System.out.print("*");
    System.out.println();
    
    System.out.print(" 30 - 39: ");
    for (int i=0;i<num30to40;i++)
        System.out.print("*");
    System.out.println();
    
    System.out.print(" 40 - 49: ");
    for (int i=0;i<num40to50;i++)
        System.out.print("*");
    System.out.println();
    
    System.out.print(" 50 - 59: ");
    for (int i=0;i<num50to60;i++)
        System.out.print("*");
    System.out.println();
    
    System.out.print(" 60 - 69: ");
    for (int i=0;i<num60to70;i++)
        System.out.print("*");
    System.out.println();
    
    System.out.print(" 70 - 79: ");
    for (int i=0;i<num70to80;i++)
        System.out.print("*");
    System.out.println();
    
    System.out.print(" 80 - 89: ");
    for (int i=0;i<num80to90;i++)
        System.out.print("*");
    System.out.println();
    
    System.out.print(" 90 -100: ");
    for (int i=0;i<num90to100;i++)
        System.out.print("*");
    System.out.println();
    
   
    
    }
}
