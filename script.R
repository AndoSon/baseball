#!/usr/bin/Rscript --vanilla --slave
mydata = read.csv("file2.csv")
library("BradleyTerry2", lib='/usr/lib64/R/library' )
#tryCatch( setwd("/home/ec2-user")  , error = function(e) print(paste("error:",e)), finally=print("123123"))
baseballModel1 <- BTm(cbind(A_score,B_score), teamA_id, teamB_id, data = mydata)
krach <- as.data.frame(BTabilities(baseballModel1))
krach <- krach[with(krach, order(-ability)), ]
krach <- subset(krach,TRUE,select=c(ability))
krach$ability <- exp(krach$ability)
write.csv(krach, file = "save.csv")
#print(krach)
