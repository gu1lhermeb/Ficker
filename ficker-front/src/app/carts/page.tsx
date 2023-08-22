"use client";
import CustomMenu from "@/components/CustomMenu";
import { Col, Row, Typography } from "antd";
import Image from "next/image";
import Link from "next/link";
import styles from "../EnterTransaction/entertransaction.module.scss";

const Carts = () => {
  const { Title, Text } = Typography;
  return (
    <div>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Link
          href={"/"}
          style={{ background: "#fff", padding: 10, alignItems: "center" }}
        >
          <Image src="/logo.png" alt="Logo" width={130} height={27} />
        </Link>
      </div>

      <div style={{ display: "flex", flexDirection: "row" }}>
        <CustomMenu />
        <Col style={{ paddingTop: 10 }} lg={20}>
          <Row justify={"space-between"} style={{ padding: 20 }}>
            <Col xs={24} lg={10}>
              <h3>Meus cartões</h3>
            </Col>
            <Col xs={24} lg={6}>
              <input className={styles.input} placeholder="Procurar..." />
              <button className={styles.button}>Novo Cartão</button>
            </Col>
          </Row>
          <Col
            style={{
              padding: 20,
              background: "#fff",
              borderRadius: 8,
              marginLeft: 20,
              boxShadow: "0px 1px 2px 2px rgba(0,0,0,0.1)",
            }}
            lg={7}
          >
            <div>
              <Row align={"middle"} style={{ marginBottom: 15 }}>
                <Image
                  src="/mastercard.png"
                  alt="Logo"
                  width={39}
                  height={30}
                />
                <Text type="secondary">Nubank</Text>
              </Row>
              <Col>
                <Text type="secondary">Próxima fatura:</Text>
                <Title level={4}>R$ 300,20</Title>
              </Col>
              <Row justify={"end"}>
                <Col>
                  <Col>
                    <Text type="secondary">Próxima fatura:</Text>
                  </Col>
                  <Row justify={"end"}>
                    <Col>
                      <Text type="secondary">10/23</Text>
                    </Col>
                  </Row>
                </Col>
              </Row>
            </div>
          </Col>
        </Col>
      </div>
    </div>
  );
};

export default Carts;
