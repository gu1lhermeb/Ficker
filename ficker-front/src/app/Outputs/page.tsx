"use client";
import Link from "next/link";
import Image from "next/image";
import styles from "../EnterTransaction/entertransaction.module.scss";
import { Col, DatePicker, Modal, Row, Select } from "antd";
import CustomMenu from "@/components/CustomMenu";
import { useState } from "react";
import type { DatePickerProps } from "antd";

const Outputs = () => {
  const [isModalOpen, setIsModalOpen] = useState(false);

  const showModal = () => {
    setIsModalOpen(true);
  };

  const handleOk = () => {
    setIsModalOpen(false);
  };

  const handleCancel = () => {
    setIsModalOpen(false);
  };

  const onChange: DatePickerProps["onChange"] = (date, dateString) => {
    console.log(date, dateString);
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
        <Modal
          title="Saídas"
          open={isModalOpen}
          onOk={handleOk}
          onCancel={handleCancel}
          okText={"Adicionar"}
          cancelText={"Cancelar"}
          okButtonProps={{
            style: {
              background: "#6C5DD3",
              border: "none",
              color: "#fff",
              borderRadius: 5,
            },
          }}
          cancelButtonProps={{
            style: {
              background: "#F8FAFC",
              border: "none",
              color: "#6C5DD3",
              borderRadius: 5,
            },
          }}
        >
          <Col>
            <Col>
              <label>Descrição</label>
            </Col>
            <Col>
              <input className={styles.input} style={{ width: "95%" }} />
            </Col>
          </Col>
          <Col style={{ marginTop: 20 }}>
            <Col>
              <label>Data:</label>
            </Col>
            <Col>
              <DatePicker
                onChange={onChange}
                className={styles.input}
                placeholder="dd/mm/aaaa"
                format={"DD/MM/YYYY"}
              />
            </Col>
          </Col>
          <Row style={{ marginTop: 20 }}>
            <Col>
              <Col>
                <label>Categoria:</label>
              </Col>
              <Col lg={24}>
                <Select
                  className={styles.input}
                  style={{ width: 150, height: 35 }}
                  options={
                    [
                      // { value: "1", label: "Alimentação" },
                      // { value: "2", label: "Educação" },
                    ]
                  }
                />
              </Col>
            </Col>
            <Col>
              <Col>
                <label>Descrição da Categoria:</label>
              </Col>
              <Col>
                <input className={styles.input} />
              </Col>
            </Col>
          </Row>
          <Col style={{ marginTop: 20, marginBottom: 20 }}>
            <Col>
              <label>Valor:</label>
            </Col>
            <Col>
              <input className={styles.input} placeholder="R$" />
            </Col>
          </Col>
        </Modal>
        <Col style={{ paddingTop: 10 }} lg={20}>
          <Row justify={"space-between"} style={{ padding: 20 }}>
            <Col xs={24} lg={10}>
              <h3>Saídas</h3>
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
                  <td style={{ color: "red" }}>-R$12.000</td>
                </tr>
              </tbody>
            </table>
          </Col>
        </Col>
      </div>
    </div>
  );
};

export default Outputs;
