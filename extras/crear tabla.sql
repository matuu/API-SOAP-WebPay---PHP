
create table WP_TRANSACCION(
    glSessionId varchar(61),
    cdOrdenCompra varchar(26),
    glTpTransaccion varchar(15),
    cdRespTbk int,
    cdAutorizacionTbk varchar(6),
    nrMonto int,
    nrTarjeta varchar(16),
    fcContableTbk varchar(4),
    fcTransaccionTbk varchar(30),
    cdTipoPago varchar(2),
    nrCuotas int,
    PRIMARY KEY (glSessionId)
);
    
     