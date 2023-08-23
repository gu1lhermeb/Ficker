"use client";
import CustomMenu from "@/components/CustomMenu";
import { Col, Row, Spin, Typography } from "antd";
import Image from "next/image";
import Link from "next/link";
import styles from "../EnterTransaction/entertransaction.module.scss";
import { NewCardModal } from "./modal";
import { useEffect, useState } from "react";
import { request } from "@/service/api";
import dayjs from "dayjs";

interface Card {
  best_day: number;
  created_at: Date;
  description: string;
  expiration: number;
  flag_id: number;
  id: number;
  updated_at: Date;
  user_id: number;
}

const Carts = () => {
  const { Title, Text } = Typography;
  const [isModalOpen, setIsModalOpen] = useState<boolean>(false);
  const [cards, setCards] = useState<Card[]>([]);
  const [loading, setLoading] = useState(false);

  const openModal = () => {
    setIsModalOpen(true);
  };

  const getCards = async () => {
    try {
      const response = await request({
        method: "GET",
        endpoint: "cards",
        loaderStateSetter: setLoading,
      });
      setCards(response.data);
    } catch (error) {
      console.log(error);
    }
  };

  const showDate = (date: number) => {
    if (date < dayjs().date()) {
      return dayjs().month() + 2;
    }
    return dayjs().month() + 1;
  };

  useEffect(() => {
    getCards();
  }, []);
  return (
    <div>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Link href={"/"} style={{ background: "#fff", padding: 10, alignItems: "center" }}>
          <Image src="/logo.png" alt="Logo" width={130} height={27} />
        </Link>
      </div>
      <NewCardModal isModalOpen={isModalOpen} setIsModalOpen={setIsModalOpen} />
      <div style={{ display: "flex", flexDirection: "row" }}>
        <CustomMenu />
        <Col style={{ paddingTop: 10 }} lg={20}>
          <Row justify={"space-between"} style={{ padding: 20 }}>
            <Col xs={24} lg={10}>
              <h3>Meus cartões</h3>
            </Col>
            <Col xs={24} lg={6}>
              <input className={styles.input} placeholder="Procurar..." />
              <button className={styles.button} onClick={openModal}>
                Novo Cartão
              </button>
            </Col>
          </Row>
          {loading ? (
            <Row justify={"center"}>
              <Spin size="large" />
            </Row>
          ) : (
            <Row justify={"start"}>
              {cards.map((card) => (
                <>
                  <Col
                    style={{
                      padding: 20,
                      background: "#fff",
                      borderRadius: 8,
                      margin: 20,
                      boxShadow: "0px 1px 2px 2px rgba(0,0,0,0.1)",
                    }}
                    lg={6}
                    xs={20}
                  >
                    <div>
                      <Row align={"middle"} style={{ marginBottom: 15 }}>
                        {card.flag_id === 1 ? (
                          <Image src={"/mastercard.png"} alt="Logo" width={39} height={30} />
                        ) : (
                          <Image src={"/visa.png"} alt="Logo" width={39} height={12} />
                        )}
                        <Text type="secondary" style={{ marginLeft: 10 }}>
                          {card.description}
                        </Text>
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
                              <Text type="secondary">
                                {card.expiration}/
                                {showDate(card.expiration) < 10
                                  ? "0" + showDate(card.expiration)
                                  : showDate(card.expiration)}
                              </Text>
                            </Col>
                          </Row>
                        </Col>
                      </Row>
                    </div>
                  </Col>
                </>
              ))}
            </Row>
          )}
        </Col>
      </div>
    </div>
  );
};

export default Carts;
