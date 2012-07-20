
package detect;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

/**
 * This class uses Greedy Tiling method to compare two array list
 * @author Yongsong
 *
 */

public class GreedyTiling{
    /**
     * The value of MIN_LENGTH is to be configured
     */
    private static final int MIN_LENGTH = 4;
    private static final int INITIAL_SEARCH_LENGTH = 20;
    private ArrayList<Integer> pattern, text;

    // int array is used to mark different colors of matching
    private int[] markPattern, markText;   // 0 means not marked
    private int markValue;
    private ArrayList<MaxMatch> queue;  // queue for storing temporary maximal match
    
    // tl1 is pattern, tl2 is text
    public GreedyTiling(ArrayList<Integer> tlPattern, ArrayList<Integer> tlText){
            pattern = tlPattern;
            text = tlText;

            markPattern = new int[pattern.size()];
            markText = new int[text.size()];
            markValue=0;
            for(int i=0; i<markPattern.length; i++){
                markPattern[i]=markValue;
            }
            for(int j=0; j<markText.length; j++){
                markText[j]=0;
            }
            // initialize the next value to be marked
            markValue =1;
            queue = new ArrayList<MaxMatch>(pattern.size()<text.size()
                    ?pattern.size():text.size());  // smaller one
    }
    
    public int scanPattern(int sl){  // sl is current search length
        HashMap<Integer, Long> patHash = new HashMap<Integer, Long>(pattern.size());
        queue.clear();
        
        long prevHash = -1, currHash;

        // this leading value is used for Rolling Hash removal of leading value
        long leadingValue = RollingHash.getLeadingValue(sl);
        for(int i=0; i< pattern.size()-sl+1; i++){
            if(markPattern[i+sl-1]>0){ // marked
                i=i+sl-1;

                prevHash =-1;
            }
            else if(markPattern[i]==0){
                if(prevHash ==-1)
                    currHash = RollingHash.getHash(pattern, i, sl);
                else
                    currHash = RollingHash.getRollingHash(pattern, i, sl,leadingValue, prevHash);
                    //RollingHash.getHash(pattern, i, sl);
                patHash.put(new Integer(i), new Long(currHash));
                
                prevHash = currHash;
            }
        }
        prevHash =-1;
        
        for(int i=0; i< text.size()-sl+1; i++){
            
            if(markText[i+sl-1] >0){ // marked
                i=i+sl-1;
                prevHash =-1;
            }
            else{
                if(prevHash ==-1)
                    currHash = RollingHash.getHash(text, i, sl);
                else
                    currHash = RollingHash.getRollingHash(text, i, sl, leadingValue, prevHash);
                    //RollingHash.getHash(text, i, sl);
                
                Iterator it = patHash.entrySet().iterator();
                
                while (it.hasNext()) {
				/**
					here should use hash table search
				*/
                    Map.Entry pair = (Map.Entry)it.next();
                    Long pairValue= (Long)pair.getValue();
                    
                    // found substrings with same hash value
                    if(currHash == pairValue.longValue()){
                        // found same hash value
                        Integer patStart = (Integer)pair.getKey();
                        int txtStart =i;
                        
                        // delay to markArray for check spurious matches
                        queue.add(new MaxMatch(patStart.intValue(), txtStart, sl));
                        
                        
                        // previous is used to check whether two hashes are consecutive
                        // if two hashes are consecutive, check them for longer search length
                        MaxMatch previous = new MaxMatch(patStart-sl, txtStart-sl,sl);
                        
                        if(patStart>=sl && queue.contains(previous)){
                                // in this case check consecutive
                                int j;
                                for(j=0; j<2*sl; j++){
                                    if(!pattern.get(previous.pat+j).equals(text.get(previous.txt+j)))
                                        break;
                                }
                                if(j==2*sl && j> INITIAL_SEARCH_LENGTH){
                                    return j;
                                }                            
                        }
                    }
                    
                    
                }
                prevHash = currHash;
            }
        }
        return sl;  // as searchLength is not double, just return searchLength 
    }

    /**
     * The top level algorithm
     */
    public void setTiles(){
        int searchLength = INITIAL_SEARCH_LENGTH;
        while(searchLength >= MIN_LENGTH){
            int max;
            System.out.println("Search Length: "+searchLength);
            while ((max = scanPattern(searchLength)) >= 2 *  searchLength){
                    searchLength = max;
            }
            //System.out.println("Max returned: "+max);
            // retreive founded maxmatch from queue
            int numMatches=markArrays();
            
            // this part is new added in for controlling the decrease rate of the search length
            int upperMatches = text.size()/searchLength;
            boolean isDense = (numMatches >= upperMatches/3)?true:false;
            
            if(searchLength >= 2* MIN_LENGTH){
                if(isDense)
                    searchLength = (int)(searchLength/1.414);
                else
                    searchLength = (int)(searchLength/1.732);  // decrease fast
            }
            
            else if(searchLength>MIN_LENGTH){
                if(isDense)
                    searchLength = (int)(searchLength/1.414);
                else
                    searchLength = MIN_LENGTH;  // decrease to MinLength;
            }
            else searchLength--;  // looping stops here
        }
    }

    public int markArrays(){
        int count=0;
        for(int i=0; i< queue.size(); i++){
            MaxMatch match = queue.get(i);

            // because mark is always from longer to shorted, only check beginning & ending
            if(  markPattern[match.pat]==0
              && markPattern[match.pat+match.len-1]==0
              && markText[match.txt]==0
              && markText[match.txt+match.len-1]==0 ){
                int j;
                for(j=0; j<match.len; j++){
                    if(!pattern.get(match.pat+j).equals(text.get(match.txt+j)))
                        break;
                    markPattern[match.pat+j]=this.markValue;
                    markText[match.txt+j]=this.markValue;
                    
                }
                if(j == match.len){  // not a spurious match
                    count++;
                    while (match.pat+j<pattern.size()&& match.txt+j<text.size() &&
                            pattern.get(match.pat+j).equals(text.get(match.txt+j))
                            && markPattern[match.pat+j]==0 &&  markText[match.txt+j]==0 ){
                        markPattern[match.pat+j]=this.markValue;
                        markText[match.txt+j]=this.markValue;
                        j++;
                    }
                    this.markValue++;
                }
                else{
                    // a revert is needed for spurious matches
                    for(int k=0; k<j; k++){
                        markPattern[match.pat+k]=0;
                        markText[match.txt+k]=0;
                    }
                }
                
            }
        }
        return count;
    }

    //length[0] is tile length, length[1] for pattern, length[2] for text
    public int[] getLengths(){
        int[] lengths = new int[3];
        
        lengths[0] =0;
        for(int i=0; i< markPattern.length; i++){
            if(markPattern[i]>0){
                lengths[0]++;
            }
        }
        
        lengths[1]= pattern.size();
        lengths[2]= text.size();
        return lengths;
    }


    // these two methods are used for generating display
    public int[] getMarkPattern(){
        return markPattern;
    }

    public int[] getMarkText(){
        return markText;
    }
    
}

class MaxMatch{
    protected final int pat;
    protected final int txt;
    protected final int len;
    
    public MaxMatch(int p, int t, int l){
        pat = p;
        txt = t;
        len = l;
    }
    
    @Override
    public boolean equals(Object obj){
        if(obj instanceof MaxMatch){
            MaxMatch another = (MaxMatch)obj;
            if(this.pat==another.pat && this.txt==another.txt && this.len==another.len)
                return true;
        }
        return false;
    }

    @Override
    public int hashCode() {
        int hash = 7;
        hash = 79 * hash + this.pat;
        hash = 79 * hash + this.txt;
        hash = 79 * hash + this.len;
        return hash;
    }
}