
package detect;

/**
 *
 * @author YONGSONG
 */
public class WeightedMajorClustering {

    private Cluster[] clusters;
    // inCluster records which cluster is the node in / or not in(-1)
    private int[] inCluster;

    private double[][] weights;
    // number of vertices
    private int length;

    public WeightedMajorClustering(Cluster[] cls, double[][] edgeWhts){
        clusters= cls;
        weights = edgeWhts;

        length = clusters.length;
        inCluster = new int[length];
        for(int i=0; i<length; i++){
            if(clusters[i]!=null){
                inCluster[i]=i;
            }
            else inCluster[i]=-1;
        }
    }

    /**
     * implement the weighted majority clustering algorithm
     */
    public void wMajorClust(){
        boolean t=false;
        while(t==false){
            t=true;
            for(int vertex=0; vertex < length; vertex++){
                if(inCluster[vertex]==-1){
                    continue;
                }
                int clusterIndex = maxNeighborCluster(vertex);
                
                if(clusters[vertex]==null || clusters[clusterIndex]==null){
                    continue;
                }
                
                if(inCluster[vertex] != clusterIndex){
                    if(clusters[clusterIndex].size()==1 && vertex< clusterIndex){       
                        clusters[vertex].add(clusterIndex);
                        inCluster[clusterIndex]=vertex;
                        clusters[clusterIndex]=null;
                    }
                    else{
                        clusters[clusterIndex].add(vertex);
                        clusters[vertex]=null;
                        inCluster[vertex]= clusterIndex;
                    }
                    t=false;
                }
            }
        }
    }


    private int maxNeighborCluster(int vertex){
        double[] neighborWeights = new double[length];
        for(int j=0; j<length; j++){
            int clusterNo = inCluster[j];
            if(clusterNo ==-1)
                continue;
            if(j<vertex){
                neighborWeights[clusterNo] += weights[j][vertex];
            }
            else if(j>vertex){
                neighborWeights[clusterNo] += weights[vertex][j];
            }
        }
        return maxClusterIndex(neighborWeights);
        
    }
    
    private int maxClusterIndex(double[] neighborWeights){
        double max=0;
        int index=-1;
        for(int i=0; i<neighborWeights.length; i++){
            if(neighborWeights[i]>max){
                max=neighborWeights[i];
                index =i;
            }
        }
        return index;
    }

    public Cluster[] getClusters(){
        return this.clusters;
    }

}
