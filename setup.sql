CREATE TABLE evento (
    idEvento int not null primary key,
    descricao varchar(500),
    localizacao varchar(200),
    valor_full float,
    valor_desconto float                
);
