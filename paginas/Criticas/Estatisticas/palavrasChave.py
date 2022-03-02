#Pegar a variável passada pelo php
import sys
estatisticasId = sys.argv[1]
#Realizar a pesquisa no mysql
import mysql.connector
mysql = mysql.connector.connect(user='', password='',
                              host='',
                              database='')
conn = mysql.cursor()
conn.execute("SELECT Descrição FROM critica WHERE ProfessorDisciplina_idProfessorDisciplina = %s", (estatisticasId,))
result = conn.fetchall()
conn.close()
mysql.close()
#Pre-processar os dados
from string import punctuation
pontuacao = punctuation
import spacy
from spacy.lang.pt.stop_words import STOP_WORDS
sw = STOP_WORDS
pln = spacy.load('pt_core_news_sm')
def preprocessar(texto):
  listaTokens = [token.lemma_ for token in pln(texto.lower())]
  listaPalavras = [token for token in listaTokens if token not in sw and token not in pontuacao]
  return [palavra for palavra in listaPalavras]
for indice, item in enumerate(result):
    item = preprocessar(item[0])
    result[indice] = item

#Coletar as palavras chave
palavrasChave = {}
for item in result:
    for palavra in item:
        if palavra not in palavrasChave:
            palavrasChave[palavra] = 1
        else:
            palavrasChave[palavra] += 1
palavrasChaveOrdenada = sorted(palavrasChave, key = palavrasChave.get,reverse=True)
rangeFor = len(palavrasChaveOrdenada)
if rangeFor > 10:
    rangeFor = 10
for i in range(rangeFor):
    print(palavrasChaveOrdenada[i] + ':' + str(palavrasChave[palavrasChaveOrdenada[i]]) + ' ')
