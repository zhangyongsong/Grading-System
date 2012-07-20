
package detect;
import java.util.*;
import java.math.BigInteger;
/**
 *
 * @author YONGSONG
 */
public class RollingHash {
    // maximum value of digits are less than radix
    private static int radix = Main.RADIX;
    private static long mod = new BigInteger(31, new Random()).longValue();
    
    public static long getHash(ArrayList<Integer> al, int start, int len){
        long hash = 0;
        for(int i=0; i< len; i++){
            int token = al.get(start+i);
            hash = (radix* hash + token)%mod;
        }
        //System.out.println(hash);
        return hash;
    }

    public static long getLeadingValue(int len){
        long leading = 1;
        for (int i = 1; i < len; i++)
           leading = (radix * leading) % mod;
        return leading;
    }

    public static long getRollingHash(ArrayList<Integer> al, int start, int len, long leading,  long prevHash ){
        int prevToken = al.get(start-1);
        // Karp Rabin algo
        long hash = (prevHash + mod - (leading* prevToken) % mod)%mod;
        
        int nextToken = al.get(start+ len -1);
        hash = (hash * radix + nextToken) % mod;
        //System.out.println(hash);
        return hash;
    }

}
