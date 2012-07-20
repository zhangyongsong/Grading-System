/*
 * A simple implementation of Cluster
 */

package detect;

import java.util.ArrayList;
/**
 * A cluster holds different files using index pointer
 * @author YONGSONG
 */
public class Cluster {
    private ArrayList documents;
    private int size;

    public Cluster(){
        documents  = new ArrayList<Integer>();
        size =0;
    }

    public void add(int docIndex){
        documents.add(docIndex);
        size++;
    }

    public int size(){
        return documents.size();
    }
    
    public int[] getElements(){
        int[] array = new int[documents.size()];
        for(int i=0; i<documents.size(); i++){
            array[i]= (Integer)documents.get(i);
        }
        return array;
    }
    
}
