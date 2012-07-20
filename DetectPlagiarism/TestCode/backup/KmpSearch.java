// this is a simple implementation of KMP search algorithm

public class KmpSearch{
	
	public static int KMP(int[] S, int[] W){
		// T is the partial match 
		int[] T = kmpTable(W);
		// m is the current S, i is the current of W
		int m=0;
		int i=0;
		while(m+i< S.length){
			if(W[i] == S[m+i]){
				if(i== W.length	-1){
					return m;  // the starting of match
				}
				i++;
				
			}
			else{
				m=m+i-T[i];
				if(T[i]>-1){
					i=T[i];
				}
				else i=0;
			}
		}
		return S.length;
	}
	
	public static int[] kmpTable(int[] W){
		int[] T= new int[W.length];
		int pos =2;
		int cnd=0;
		
		T[0]=-1;
		T[1]=0;
		
		while(pos < W.length){
			if(W[pos-1] == W[cnd]){
				cnd++;
				T[pos]=cnd;
				pos++;
			}
			else if(cnd>0){
				cnd = T[cnd];
			}
			else{
				T[pos]=0;
				pos++;
			}
		}
		return T;
	}
		
	public static void main(String[] args){
		int[] search ={1,2,3,7,7,5, 4, 5,7,6,5, 7,6,7,7,5, 4, 5,7, 6,4,5,4};
		int[] word = {7,7,5, 4, 5,7,6,4};
		System.out.println(KMP(search, word));
	}
}