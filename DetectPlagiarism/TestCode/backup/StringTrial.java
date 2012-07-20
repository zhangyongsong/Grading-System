// comments

//package Trial;

//import java.util.Scanner;
public class StringTrial{
	public static void main(String[] Args){
		//Scanner sc= new Scanner(System.in);
		String str="12";
		System.out.println(str+str.length());
		str=str+str;
		System.out.println(str);
		System.out.println();
		
		str=" I can change a STRING totally like this ";
		System.out.println(str);
		str.toUpperCase();
		 
		System.out.println(str);
		str=str.toLowerCase();
		System.out.println(str);
		str.trim();
		System.out.println(str);
	}
}