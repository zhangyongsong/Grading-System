public class Template{
	public static void main(String[] Args){
//		Scanner sc= new Scanner(System.in);
		
		
		{
			int a=2, b=3;
			System.out.println(a+b);
			System.out.println(a+"  "+b);
		}

		
		int a=56;
		System.out.println(a);
		
		String str="I love you a lot!";
		System.out.println(str);
		System.out.println(encrypt(str));
		
		System.out.println(encryptMessage("ABZ"));

		printTriangle(4);
		
		String aString;
		aString="\nCome on";
		
		System.out.println(aString);
 	}
 
	
	public static String encryptMessage(String message){
		String str="";
		int i;	
		char ch;	
		int num;
		
		for(i=message.length(); i>0; i--){
			ch=message.charAt(i-1);
			num=(int)ch;
			str=str+num;
		}
		
		return str;
	}

	
	public static void printTriangle(int size){
		int i, j;
		for(j=1;j<=size;j++)
			System.out.print(1+"\t");
		System.out.println();
		
		for(i=2; i<size; i++){
			System.out.print(i);
			for(j=i; j<size; j++)
				System.out.print("\t");
			System.out.println(i);
		}
		
		System.out.print(size);
	}
	public static String encrypt(String str){
		String str1="";
		char ch, cha;
		int counter=0;

		while(counter<str.length()){
			ch=str.charAt(counter);
			if((int)ch>=65 && (int)ch<91)
			cha=(char)(91-(int)ch+64);
			else if((int)ch>=97 && (int)ch<123 )
				cha=(char)(123-(int)ch+96);
			else cha=ch;
			
			str1+=cha;
			counter++;
		}
		
		return str1;
	}
}