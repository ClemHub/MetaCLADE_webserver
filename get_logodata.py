#!/usr/bin/env python3
import argparse, os, json, gzip, configparser
import pandas as pd
import logomaker as lm
import matplotlib.pyplot as plt

def filter_model(f_hmm, target):
    models =  ''.join(f_hmm.readlines()).split('//')
    for model in models:
        lines = model.split('\n')
        for l in lines:
            fields = l.split()
            if len(fields)==0:
                continue
            if fields[0]=="NAME" and fields[1]== target:
                return model
    return None


def parse_hmmsearch_output(f_hmm):
    lines=f_hmm.readlines()
    i=0
    hits={}
    while i<len(lines):
        l = lines[i].replace('\n','')
        if len(l)==0:
            i+=1
            continue
        if l[0]=="#":
            i+=1
            continue
        if l=="//":
            i+=1
            continue
        if l[:6]=="Query:":
            modelID = l.split()[1]
            i+=1
            continue
        if l[:2]==">>":
            seqID = l.split()[1]
            #seqID = seqID.replace('_','').replace('.','')

            i+=1
            if lines[i].replace(" ","")[0]!="#":#The next line is not a table. Probably beause no domain satisfies the thresholds
                continue
            hits[(modelID,seqID)]={}
            i+=2
            while 1:
                fields = lines[i].split()
                try:

                    _id = int(fields[0])
                    i+=1
                except:
                    break
                                #match_on_model,evalue,hmmfrom,hmmto,envfrom, envto
                hits[(modelID,seqID)][_id]={"hmm_match":"","sequence_match":"",
                                            "evalue":float(fields[5]),
                                            "hmmfrom":int(fields[6]), "hmmto":int(fields[7]),
                                            "alifrom":int(fields[9]), "alito":int(fields[10]),
                                            "envfrom":int(fields[12]),"envto":int(fields[13])
                                            }

        if l[2:4]=="==":
            fields = l.split()
            domId  = int(fields[2])
            i+=1

            annotation_lines=0
            while lines[i+annotation_lines][:-1].split()[0]!=modelID:
                annotation_lines+=1
            while 1:
                try:
                    modelID, modStart, model_match, modEnd = lines[i+annotation_lines][:-1].split()
                except:
                    print(i+annotation_lines,":",lines[i+annotation_lines][:-1])
                    raise
                seqID,   seqStart, seq_match,   seqEnd = lines[i+2+annotation_lines][:-1].split()

                #seqID = seqID.replace('_','').replace('.','')                
                if (modelID,seqID) in hits:
                    hits[(modelID,seqID)][domId]["hmm_match"]      += model_match
                    hits[(modelID,seqID)][domId]["sequence_match"] += seq_match
                i+=(5+annotation_lines)

                if int(modEnd) == hits[(modelID,seqID)][domId]["hmmto"]:
                    i-=1
                    break
        i+=1
    return hits
        


# In[25]:


def makeLogoData(hmmlogo_exec, hmm_file):
    aa="ACDEFGHIKLMNPQRSTVWY"
    data={}
    data["alphabet"]="aa"
    data["processing"]="hmm"

    data["ali_map"]=[]
    data["heightArr"]=[]

    data["min_height_obs"]=0. #To calculate
    data["max_height_obs"]=0. #To calculate


    data["sumH"]={}
    data["insert_probs"] = []
    data["insert_lengths"] = []
    data["delete_probs"] = []
    cmd = f"{hmmlogo_exec} {hmm_file}"
    print(cmd)
    with os.popen(cmd) as f:
        lines=f.readlines()
    i=0 
    while i < len(lines):
        l=lines[i].replace("\n","")

        if l[:19]=="max expected height":
            data["max_height_theory"]=float(l.split("=")[1])
        elif l=="Residue heights":
            i+=1
            while i < len(lines):
                l=lines[i].replace("\n","").replace("(","").replace(")","")
                try:
                    resId, records = l.split(':')
                except ValueError:
                    i-=1
                    break

                resId = int(resId)
                data["ali_map"].append(resId)
                heights = records.split()
                #data["sumH"][resId] = float(heights[-1])
                heights = [ float(r) for r in heights[:-1]]
                data["min_height_obs"] = data["min_height_obs"] if min(heights)>data["min_height_obs"] else min(heights) 
                data["max_height_obs"] = data["max_height_obs"] if max(heights)<data["max_height_obs"] else max(heights) 
                heights=sorted([ (aa[j],r) for (j,r) in enumerate(heights)], key=lambda item: item[1])
                data["heightArr"].append( dict(heights) )
                i+=1
        elif l=="Indel values":
            i+=1
            while i < len(lines):
                l=lines[i].replace("\n","")
                try:
                    resId, records = l.split(':')
                except ValueError:
                    i-=1
                    break            
                resId = int(resId)
                records=records.split()
                data["insert_probs"].append(float(records[0]))
                data["insert_lengths"].append(float(records[1]))
                data["delete_probs"].append(float(records[2]))
                i+=1
        i+=1
    data["mmline"]=[0]*len(data["ali_map"]) 
    return data

def makeLogoImg(data, fn_name,dpi=None):
    colors = { #C is missing
            "G":"#ff7f11",
            "P":"#f5f520",
            "A":"#8e8efd",
            "V":"#8e8efd",
            "L":"#8e8efd",
            "I":"#8e8efd",
            "M":"#8e8efd",
            "F":"#8e8efd",
            "W":"#8e8efd",
            "S":"#4fe64f",
            "T":"#4fe64f",
            "N":"#4fe64f",
            "Q":"#4fe64f",
            "D":"#FF0000",
            "E":"#FF0000",
            "R":"#FF0000",
            "K":"#FF0000",
            "H":"#30ecec",
            "Y":"#30ecec"
            }
    freq = data["heightArr"]
    header = sorted(list(freq[0].keys()))

    pd_list = [] 
    for f in freq:
        pd_list.append( [ f[aa] for aa in header] )
    df=pd.DataFrame(pd_list, columns=header)
    logo = lm.Logo(df, color_scheme=colors, width=1)
    logo.style_spines(visible=False)
    logo.fig.axes[0].yaxis.set_ticks([])
    logo.fig.axes[0].xaxis.set_ticks([])
    # create figure
    ticks = [ x for x in range(0, len(df), 10)]

    logo.fig.set_figheight(2.5)
    logo.fig.set_figwidth(0.2*len(df))

    logo.fig.savefig(fn_name, bbox_inches='tight',transparent=True, pad_inches=0, dpi=dpi)
    plt.close(logo.fig)


#Compares a sequence with consensus data got from hmmlogo.
#The output is a tuple made out of two strings.
#The former is a string (S) as long as sequence. 
#S[i] is "*" if sequence[i] is equal to the most frequent letter in the consensus
#S[i] is "+" if sequence[i] is equal to the class of the most frequent letter in the consensus
#S[i] is " " (space) if neither of the previous cases
#The latter is the sequence on the database where the insertion have been removed and some trailing or tailing spaces are added in order to make it match the logo position
def make_match_sequence(logo_data,sequence):

    """    
                hits[(modelID,seqID)][_id]={"hmm_match":"","sequence_match":"",
                                            "evalue":float(fields[5]),
                                            "hmmfrom":int(fields[6]), "hmmto":int(fields[7]),
                                            "envfrom":int(fields[12]),"envto":int(fields[13])
                                            }
    """                                            
#Categories taken from http://skylign.org/help
    classes = {
            "C":"C",
            "G":"G",
            "P":"P",
            "A":"small_hydrophobic",
            "V":"small_hydrophobic",
            "L":"small_hydrophobic",
            "I":"small_hydrophobic",
            "M":"small_hydrophobic",
            "F":"small_hydrophobic",
            "W":"small_hydrophobic",
            "S":"Hydroxyl_amin",
            "T":"Hydroxyl_amin",
            "N":"Hydroxyl_amin",
            "Q":"Hydroxyl_amin",
            "D":"charged",
            "E":"charged",
            "R":"charged",
            "K":"charged",
            "H":"hystidine_tyrosine",
            "Y":"hystidine_tyrosine",
            }

    logo_sequence = [ list(p.keys())[-1]  for p in logo_data["heightArr"] ] #getting the sequence of the most present letter in the logo
    logo_sequence = ''.join(logo_sequence)


    align_hmm_seq = [ aa_seq for (aa_hmm,aa_seq) in zip(X["hmm_match"],X["sequence_match"]) if aa_hmm!="-" and aa_hmm!='.'] #getting the part of the sequence matching the hmm


#If the sequence match doesn't span the whole model, adding some space for having a match on the sequence as long as the logo
    align_hmm_seq = [" "]*(X["hmmfrom"]-1)+align_hmm_seq+[" "]*(len(logo_sequence)-X["hmmto"])
    align_hmm_seq = ''.join(align_hmm_seq)
    
    assert len(align_hmm_seq)==len(logo_sequence), f"align and logo have different length:\n{align_hmm_seq}\n{logo_sequence}"
    
    match_str =""
    for aa_logo, aa_sequence in zip(logo_sequence,align_hmm_seq):
        if aa_logo==aa_sequence:    
            match_str+="*"
        elif aa_sequence in [' ','-']:
            match_str+=" "
        elif classes[aa_logo] == classes[aa_sequence]:
            match_str+="+"
        else:
            match_str+=" "
    return match_str, align_hmm_seq



if __name__ =="__main__":
    parser = argparse.ArgumentParser(description='Create json file for using with hmm-logo library ')
    parser.add_argument('--work_dir', required=True,help="Directory where the output will be saved")
    parser.add_argument('--dpi',    type=int,      help="Resolution in dpi of output images")
    parser.add_argument('--mclade_cfg', help="Metaclade2 configuration file")
    args = parser.parse_args()
    
    cladeCfgParser  = configparser.ConfigParser(allow_no_value=True)
    cladeCfgParser.read(args.mclade_cfg)
    folder_hmms = cladeCfgParser.get( "metaclade", "hmms_path")
    folder_ccms = cladeCfgParser.get( "metaclade", "ccms_path")
    hmmerpath      = cladeCfgParser.get( "programs", "hmmer_path")
    hmmsearch_exec = cladeCfgParser.get( "programs", "hmmsearch_exec")
    hmmlogo_exec = f'{hmmerpath}/hmmlogo'

    df=pd.read_csv(f'{args.work_dir}/results.txt', sep="\t")
    groups = df.groupby(by=["Domain ID","Model ID"])
    with open(args.work_dir+"/match.txt", "w") as fout:
        fout.write("DomainId\tModel start\tModel stop\tSeqID\tSeq start\tSeq stop\tE-value\tmatch\n")
        for (domId, modId) in groups.groups.keys():
            folder = folder_hmms if modId=="HMMer-3" else folder_ccms
            suffix = "hmm" if modId=="HMMer-3" else "ccms"
            
            hmm_file = f"{folder}/{domId}.hmm.gz"
            if suffix=="ccms":
            #ccms have several model concatenated. So before going on, the right one have to be extracted
                with gzip.open(hmm_file, "rt") as fin_hmm:
                    model=filter_model(fin_hmm, modId)
                    if model!=None:
                        fn_model = f"{args.work_dir}/temp/{domId}_{modId}.hmm"
                        with open(fn_model,"wt") as fout_hmm:
                            fout_hmm.writelines(model+"\n//")
            else:
                fn_model = hmm_file
            data=makeLogoData(hmmlogo_exec, fn_model)

            if args.dpi==None:
                fn_img=f"{args.work_dir}/{domId}.{modId}.{suffix}.svg"
            else:
                fn_img=f"{args.work_dir}/{domId}.{modId}.{suffix}.png"

            makeLogoImg(data,fn_img,dpi=args.dpi)



            cmd=f"{hmmerpath}/{hmmsearch_exec} -T 0  --domE 1 --cpu 1 {fn_model} {args.work_dir}/data.fa"
            print(cmd)
            rows = df[ df["Domain ID"]==domId]
            with os.popen(cmd) as f:
                parsed_data=parse_hmmsearch_output(f)
                for (modelId,seqId) in parsed_data:
                    for domain in parsed_data[(modelId,seqId)]:
                        X=parsed_data[(modelId,seqId)][domain]

                        match_vs_consensus, sequence_vs_consensus = make_match_sequence(data,X)

                        fout.write(f"{seqId},")
                        fout.write(f"{X['envfrom']},{X['envto']},")
                        fout.write(f"{X['alifrom']},{X['alito']},")
                        fout.write(f"{domId},")
                        fout.write(f"{X['hmmfrom']},{X['hmmto']},")
                        fout.write(f"{X['evalue']},")
                        fout.write(f"{sequence_vs_consensus},")
                        fout.write(f"{match_vs_consensus}")
                        fout.write('\n')



