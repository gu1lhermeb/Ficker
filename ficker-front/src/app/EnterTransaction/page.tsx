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
      <div style={{ background: "#fff", padding: 10, alignItems: "center"}}>
        <Link href={"/"} style={{ background: "#fff", padding: 10, alignItems: "center" }}>
          <Image src="/logo.png" alt="Logo" width={130} height={27} />
        </Link>
      </div>
      <div style={{ display: "flex", flexDirection: "row" }}>
        <CustomMenu />
        <EnterTransactionModal isModalOpen={isModalOpen} setIsModalOpen={setIsModalOpen} />
        <Col style={{ paddingTop: 10 }} lg={19}>
          <Row justify={"space-between"} style={{ padding: 20 }}>
            <Col xs={24} lg={10}>
              <h3>Entradas</h3>
            </Col>
            <Col xs={24} lg={7}>
              <input className={styles.input} placeholder="Procurar..." />
              <button className={styles.button} onClick={showModal}>
                Nova Transação
              </button>
            </Col>
          </Row>
          <Col xs={20} lg={22}>
            <table className={styles.table}>
              <thead className={styles.thead}>
                <tr>
                  <th>Editar</th>
                  <th>Descrição</th>
                  <th>Data</th>
                  <th>Categoria</th>
                  <th>Valor</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td className={styles.tdEdit}>
                    <button style={{ background: "none", border: "none" }} onClick={() => {}}>
                      <Image src="/edit.png" alt="Editar" width={20} height={20} />
                    </button>
                  </td>
                  <td className={styles.tdDescription}>Curso de Java</td>
                  <td className={styles.tdDate}>13/04/2023</td>
                  <td className={styles.tdCategory}>Outros</td>
                  <td className={styles.tdValue} style={{ color: "green" }}>R$12.000</td>
                </tr>
                <tr>
                  <td className={styles.tdEdit}>
                    <button style={{ background: "none", border: "none" }} onClick={() => {}}>
                      <Image src="/edit.png" alt="Editar" width={20} height={20} />
                    </button>
                  </td>
                  <td className={styles.tdDescription}>Lorem ipsum dolor sit amet consectetur adipisicing elit.</td>
                  <td className={styles.tdDate}>13/04/2023</td>
                  <td className={styles.tdCategory}>Outros</td>
                  <td className={styles.tdValue} style={{ color: "green" }}>R$12.000</td>
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
