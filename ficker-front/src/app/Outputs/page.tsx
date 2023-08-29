"use client";
import Link from "next/link";
import Image from "next/image";
import styles from "../EnterTransaction/entertransaction.module.scss";
import { Col, Row, Spin } from "antd";
import CustomMenu from "@/components/CustomMenu";
import { useEffect, useState } from "react";
import { OutputModal } from "./modal";
import { request } from "@/service/api";
import dayjs from "dayjs";

interface Transaction {
  id: number;
  user_id: number;
  category_id: number;
  card_id: number;
  description: string;
  date: Date;
  type: string;
  value: number;
  installments: number;
  created_at: Date;
  updated_at: Date;
}

const Outputs = () => {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [transactions, setTransactions] = useState<Transaction[]>([]);
  const [loading, setLoading] = useState(false);
  const showModal = () => {
    setIsModalOpen(true);
  };

  const getTransactions = async () => {
    try {
      const response = await request({
        method: "GET",
        endpoint: "transactions",
        loaderStateSetter: setLoading,
      });
      setTransactions(response.data);
    } catch (error) {
      console.log(error);
    }
  };

  useEffect(() => {
    getTransactions();
  }, [isModalOpen]);

  return (
    <div>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Link href={"/"} style={{ background: "#fff", padding: 10, alignItems: "center" }}>
          <Image src="/logo.png" alt="Logo" width={130} height={27} />
        </Link>
      </div>
      <div style={{ display: "flex", flexDirection: "row" }}>
        <CustomMenu />
        <OutputModal isModalOpen={isModalOpen} setIsModalOpen={setIsModalOpen} />
        <Col style={{ paddingTop: 10 }} lg={19}>
          <Row justify={"space-between"} style={{ padding: 20 }}>
            <Col xs={24} lg={10}>
              <h3>Saídas</h3>
            </Col>
            <Col xs={24} lg={7}>
              <input className={styles.input} placeholder="Procurar..." />
              <button className={styles.button} onClick={showModal}>
                Nova Transação
              </button>
            </Col>
          </Row>
          {loading ? (
            <Col xl={22}>
              <Row justify={"center"}>
                <Spin size="large" />
              </Row>
            </Col>
          ) : (
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
                  <>
                    {transactions?.map((transaction) => (
                      <tr key={transaction.id}>
                        <td className={styles.tdEdit}>
                          <button style={{ background: "none", border: "none" }} onClick={() => {}}>
                            <Image src="/edit.png" alt="Editar" width={20} height={20} />
                          </button>
                        </td>
                        <td className={styles.tdDescription}>{transaction.description}</td>
                        <td className={styles.tdDate}>{dayjs(transaction.date).format("DD/MM/YYYY")}</td>
                        <td className={styles.tdCategory}>Outros</td>
                        <td className={styles.tdValue} style={{ color: "red" }}>-R${transaction.value}</td>
                      </tr>
                    ))}
                  </>
                </tbody>
              </table>
            </Col>
          )}
        </Col>
      </div>
    </div>
  );
};

export default Outputs;
