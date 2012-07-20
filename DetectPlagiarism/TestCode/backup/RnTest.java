import java.util.Scanner;

public class RnTest{
	public static void main(String[] args) throws Exception{
		Scanner sc = new Scanner(System.in);
		System.out.println("Please enter a value less than 4,000,000:");
		RomanNumeral input= new RomanNumeral(sc.nextInt());
		RomanNumeral currentYear= new RomanNumeral("MMXI");
		RomanNumeral offset = new RomanNumeral(15);
		System.out.println("You have entered "+ input.getRomanString()+ ", which is "+input.getNumber()+".");
		System.out.println(currentYear.getRomanString() + " is the current year, which is "+ currentYear.getNumber()+".");
        System.out.print("In "+ offset.getRomanString() + "'s years, I would be older!");
	}
}