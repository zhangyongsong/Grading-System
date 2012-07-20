import java.io.IOException;

public class Run{
	public static void main(String[] args) 
	  throws IOException{
		try{
			Runtime.getRuntime().exec("mspaint.exe");
			}catch(IOException e){
				e.printStackTrace();
			}
		return;
	}
}