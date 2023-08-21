"use client";
import Link from "next/link";
import Image from "next/image";
import styles from "./entertransaction.module.scss";
import { Col, Row } from "antd";
import CustomMenu from "@/components/CustomMenu";
import { useState } from "react";
import {EnterTransactionModal} from "./modal";

const EnterTransaction = () => {3
  const [isModalOpen, setIsModalOpen] = useState(false);

  const showModal = () => {
    setIsModalOpen(true);
  };
  return (
    <div>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Link href={"/"} style={{ background: "#fff", padding: 10, alignItems: "center" }}>
          <Image src="/logo.png" alt="Logo" width={130} height={27} />
        </Link>
      </div>
      <div style={{ display: "flex", flexDirection: "row" }}>
        <CustomMenu />
        <EnterTransactionModal isModalOpen={isModalOpen} setIsModalOpen={setIsModalOpen} />
        <Col style={{ paddingTop: 10 }} lg={20}>
          <Row justify={"space-between"} style={{ padding: 20 }}>
            <Col xs={24} lg={10}>
              <h3>Entradas</h3>
            </Col>
            <Col xs={24} lg={6}>
              <input className={styles.input} placeholder="Procurar..." />
              <button className={styles.button} onClick={showModal}>
                Nova Transação
              </button>
            </Col>
          </Row>
          <Col xs={20} lg={24}>
            <table className={styles.table}>
              <thead>
                <tr>
                  <th style={{ width: 100 }}>Editar</th>
                  <th>Descrição</th>
                  <th style={{ width: 200 }}>Data</th>
                  <th style={{ width: 150 }}>Valor</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style={{ display: "flex", justifyContent: "center", alignItems: "center" }}>
                    <button style={{ background: "none", border: "none" }} onClick={() => {}}>
                      <Image src="/edit.png" alt="Editar" width={20} height={20} />
                    </button>
                  </td>
                  <td>Curso de Java</td>
                  <td>13/04/2023</td>
                  <td style={{ color: "green" }}>R$12.000</td>
                </tr>
              </tbody>
            </table>
          </Col>
        </Col>
      </div>
    </div>
  );
};

export default EnterTransaction;
