// this program tries to scan the ports 

import java.net.*;
import java.io.*;
import java.util.Scanner;

public class PortScanner{
	public static void main(String[] args){
		String host;
		// first determine the host String
		if(args.length==0){
			Scanner sc=new Scanner(System.in);
			System.out.print("Enter the Host name for port scanning: ");
			host = sc.nextLine();
		}
		else{
			host = args[0];
		}
		
		try{
			InetAddress hostAddr = InetAddress.getByName(host);
			for (int i=0; i<65536; i++){
				Socket skt = null;
				try{
					skt = new Socket(host, i);
					System.out.println("There is a server on port "+ i + " for host "+ host +".");
				} catch (IOException ex){
					// this means that this port number is not a server
				}
				finally{
					try{
						if(skt != null)
							skt.close();
					}catch(IOException ex){					
					}
				}
			}
		} catch(UnknownHostException ex){
			System.out.println("Unknown Host. Cannot Connect to it!");
		}
	}
}