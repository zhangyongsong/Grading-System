
package detect;

import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.HashMap;

/**
 *
 * @author YONGSONG
 */
public class HierarchicalComparison {
    // sCount is the count of source files
    private int sCount;
    private String[] sourceId;
    private String[] originalSources;
    private String[] modifiedSources;

    private LexicalParser[] parsers;

    /**
     * reservedList holds for level 1 scan of reserved words only
     */
    private ArrayList<Integer>[] reservedList;
    private ArrayList<Integer>[] allList;
    
    private HashMap<Integer, String> color;
    private String[][][] strReport;// for comparison results
    private String[][] strContents;  // strContents for text

    /**
     * candidate clusters
     */
    private Cluster[] clusters;
    private double[][] edgeWeights;

    public HierarchicalComparison(String[] names, String[] oldSources, String[] newSources){
        sourceId = names;
        originalSources = oldSources;
        modifiedSources = newSources;

        sCount = sourceId.length;
        parsers = new LexicalParser[sCount];

        reservedList = new ArrayList[sCount];
        allList = new ArrayList[sCount];
        
        strReport = new String[sCount][sCount][3];
        strContents = new String[sCount][sCount];
        
        clusters =  new Cluster[sCount];
        edgeWeights = new double[sCount][sCount];
        
        
        color = new HashMap();
        color.put(0, "#FFFF00");
        color.put(1, "#FF0000");
        color.put(2, "#00FF00");
        color.put(3, "#0000FF");
        color.put(4, "#00FFFF");
        color.put(5, "#FF00FF");
    }

    public void pairComparison(){
        for(int i=0; i<sCount; i++){
            parsers[i] = new LexicalParser(modifiedSources[i]);
            reservedList[i]= parsers[i].getReservedTokens();
        }

        for(int j=0; j<sCount; j++){
          for(int k=j+1; k<sCount; k++){
              for(int m=0;m<strReport[j][k].length;m++){
                  strReport[j][k][m]="";
              }
              
              String strHead="";
              strReport[j][k][0]=sourceId[j]+" VS "+ sourceId[k];
              strHead +=  strReport[j][k][0]+"\n";
              
              System.out.println("Tiling for "+ strReport[j][k][0]+":");
              // Hash based String similarity processing of parsed integers using RKR-GST
              GreedyTiling gt = new GreedyTiling(reservedList[j], reservedList[k]);
              gt.setTiles();
              int[] reservedLength = gt.getLengths();
              strHead += "Reserved Comparison Lengths: "+ reservedLength[0]+" "+
                      reservedLength[1]+" "+reservedLength[2]+"\n";
              double sim =calSimilarity(reservedLength);
              strReport[j][k][1]=sim+"";
              strHead += "Similarity: "+sim +"\n";

              if(sim>Main.RESERVED_THRESHOLD){
                if(allList[j]==null){
                    allList[j]=parsers[j].getAllTokens();
                }
                if(allList[k]==null){
                    allList[k]=parsers[k].getAllTokens();
                }

                GreedyTiling gtl = new GreedyTiling(allList[j], allList[k]);
                gtl.setTiles();
                int[] allLength = gtl.getLengths();
                strHead+="All Tokens Comparison Lengths: "+ allLength[0]
                        +" "+allLength[1]+" "+allLength[2]+"\n";

                double allSim = calSimilarity(allLength);
                strHead += "Similarity: "+allSim +"\n";

                strReport[j][k][2]=allSim+"";
                
                if(allSim > Main.CUTOFF){
                    strReport[j][k][0]="<a href=\""+sourceId[j]+"-"+sourceId[k]+".html"+"\">"
                            +strReport[j][k][0]+"</a>";

                    String patResult = markSame(j, parsers[j].getAllStarts(),
                            parsers[j].getAllEnds(), gtl.getMarkPattern());
                    String txtResult = markSame(k, parsers[k].getAllStarts(),
                            parsers[k].getAllEnds(), gtl.getMarkText());
                    strContents[j][k] ="<table><tr>";
                    strContents[j][k]+="<td><pre>"+patResult+"</pre></td>";
                    strContents[j][k]+="<td><pre>"+txtResult+"</pre></td>";
                    strContents[j][k]+="</tr></table>";

                    strContents[j][k]="<pre>"+strHead+"\n</pre>"+strContents[j][k];
                    clusters[j]=new Cluster();
                    clusters[k]=new Cluster();
                    clusters[j].add(j);
                    clusters[k].add(k);
                    edgeWeights[j][k]=allSim;
                  }
              }
            }
          }
    }

    protected String markSame(int iter, ArrayList<Integer> starts, ArrayList<Integer> ends,
        int[] marked ){
        StringBuilder sb = new StringBuilder(originalSources[iter]);
        // the searching is from back till
        int start, end;
        String close="</font>";
        
        int inMarkValue=0;
        for(int j= marked.length-1; j>=0; j--){
            if(marked[j]>0 && inMarkValue==0){
                end = ends.get(j);
                sb.insert(end, close);
                inMarkValue = marked[j];
            }
            if(inMarkValue>0 && marked[j]!=inMarkValue){
                start = starts.get(j);
                String open="<font color=\""+color.get(inMarkValue%6) +"\">"; 
                sb.insert(start, open);
                
                if(marked[j]>0){
                    sb.insert(start, close);
                }
                inMarkValue=marked[j];
            }
        }

        if(inMarkValue>0){
                start = starts.get(0);
                String open="<font color=\""+color.get(inMarkValue%6) +"\">"; 
                sb.insert(start, open);
        }

        return sb.toString();
    }

    /**
     *
     * @param lengths
     * @return similarity
     */
    protected double calSimilarity(int[] lengths){
        double sim = lengths[0]/(double)Math.max(lengths[1], lengths[2]);
        DecimalFormat threeDForm = new DecimalFormat("#.###");
        return Double.valueOf(threeDForm.format(sim));
    }

    public Cluster[] getClusters(){
        return clusters;
    }

    public double[][] getEdgeWeights(){
        return edgeWeights;
    }

    
    public String[][][] getOutputs(){
        return strReport;
    }
    
    public String[][] getMarkedTexts(){
        return strContents;
    }
}
