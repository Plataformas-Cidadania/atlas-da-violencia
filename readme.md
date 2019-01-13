# site-violencia

codigos PHP Atlas da VIolencia (e outros)

Instruções para colocar em produção:

solicito colocar em produção o Atlas do Estado Brasileiro 
(ainda não aberto ao público - ficará em http://www.ipea.gov.br/atlasestado e http://atlasestado.ipea.gov.br ). 
Para isto: 
1-copiar a pasta /var/www/html/estadobrasileiro da máquina atlasvl-homologa.ipea.gov.br para a maquina de produção

2-copiar o bd atlasvl_estados_homologa da máquina atlasvl-homologa.ipea.gov.br para a máquina de produção 

3-ajustar o arquivo .env (na raiz do sistema) para a url e conexões ao banco de dados 
    (.env: alterar APP_ENV=local -> mudar de local para production APP_DEBUG=true -> mudar para false)
    
4-ajustar o subdomínio do Apache para que aponte para a pasta //var/www/html/atlasviolencia/public 

5-copiar o arquivo /etc/apache2/sites-available/atlasestado.conf (de homologacao para producao)

6-reiniciar o Apache 

7-para testar, abrir http://atlasestado.ipea.gov.br (deverá abrir o Atlas do Estado, tal como está em http://atlasvl-homologa.ipea.gov.br/estadobrasileiro/) 

obs: nao vejo as pastas em homologacao, podem ser atlasestado ou estadobrasileiro. 
Para o publico externo usaremos atlasestado 
alternativamente, usar o jenkins 
o codigo deste Atlas do Estado é o mesmo do Atlas da Violencia 